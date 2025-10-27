<?php

namespace App\Console\Commands; 

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Movie; 
use App\Models\Category; 
use Illuminate\Support\Str;
use Throwable;

class PopulateMoviesFromTMDb extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'movies:populate {--pages=1 : Number of pages to fetch from TMDb}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Populate the movies table with popular movies (and genres) from TMDb';

    /**
     * Fetch the genre mapping from TMDb or cache.
     * Caches the result for 1 day to minimize API calls.
     * @return array Map of TMDb genre ID to genre name.
     */
    private function getGenreMap(): array
    {
        return Cache::remember('tmdb_genre_map', 86400, function () { // Cache for 86400 seconds (1 day)
            $apiKey = config('services.tmdb.api_key');
            if (!$apiKey) {
                $this->error('TMDB_API_KEY is not configured for genre fetching.');
                return [];
            }

            // Fetch the official genre list from TMDb (English names)
            $response = Http::get("https://api.themoviedb.org/3/genre/movie/list?api_key={$apiKey}&language=en-US");

            if ($response->failed()) {
                $this->error('Failed to fetch genre list from TMDb.');
                return [];
            }

            $genres = $response->json()['genres'] ?? [];
            $map = [];
            // Create a map like [ 28 => 'Action', 12 => 'Adventure' ]
            foreach ($genres as $genre) {
                if (isset($genre['id'], $genre['name'])) {
                    $map[$genre['id']] = $genre['name'];
                }
            }

            // Only log when fetching directly from API, not from cache
            if (!Cache::has('tmdb_genre_map') && !empty($map)) {
                 $this->info('Fetched and cached genre map from TMDb.');
            }
            return $map;
        });
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to populate movies from TMDb...');
        $apiKey = config('services.tmdb.api_key');
        if (!$apiKey) {
            $this->error('Error: TMDB_API_KEY is not configured.');
            return 1; // Indicate failure
        }

        $genreMap = $this->getGenreMap();
        if (empty($genreMap)) {
            $this->error('Could not retrieve genre map. Aborting population.');
            return 1; // Indicate failure
        }

        $pagesToFetch = (int) $this->option('pages');
        $moviesAddedOrUpdated = 0;

        for ($page = 1; $page <= $pagesToFetch; $page++) {
            $this->info("Fetching page {$page} of popular movies...");
            // Fetch popular movies (can be changed to top_rated etc.)
            $response = Http::get("https://api.themoviedb.org/3/movie/popular?api_key={$apiKey}&language=en-US&page={$page}");

            if ($response->failed()) {
                $this->error("Failed to connect to TMDb API (popular) on page {$page}. Stopping.");
                break; // Stop if API fails
            }

            $moviesFromApi = $response->json()['results'] ?? []; 

            if (empty($moviesFromApi)) {
                $this->info("No more movies found on page {$page}. Stopping.");
                break; // Stop if no results on page
            }

            foreach ($moviesFromApi as $movieData) {
                try {
                    // Skip if essential data is missing from API response
                    if (empty($movieData['id']) || empty($movieData['title'])) {
                        $this->warn("Skipping movie with missing ID or Title.");
                        continue;
                    }

                    // Use updateOrCreate to avoid duplicates based on tmdb_id
                    $movie = Movie::updateOrCreate(
                        ['tmdb_id' => $movieData['id']], // Find condition
                        [ // Data to create or update with
                            'title' => $movieData['title'],
                            'slug' => Str::slug($movieData['title']) . '-' . $movieData['id'], // Create a unique slug
                            'description' => $movieData['overview'] ?? '', // Default to empty string if null
                            'release_year' => isset($movieData['release_date']) && strlen($movieData['release_date']) >= 4 ? Str::substr($movieData['release_date'], 0, 4) : null,
                            'poster_url' => isset($movieData['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $movieData['poster_path'] : null, // Vertical poster image
                            // 'backdrop_url' => isset($movieData['backdrop_path']) ? 'https://image.tmdb.org/t/p/w1280' . $movieData['backdrop_path'] : null, // Horizontal backdrop (requires DB column)
                            'imdb_rating' => isset($movieData['vote_average']) ? round($movieData['vote_average'], 1) : null,
                            'is_featured' => false, // Default to not featured; only trending command sets this
                            'trailer_url' => null, // Trailer logic can be added here if needed later
                        ]
                    );

                    // --- START GENRE SYNCING ---
                    if (isset($movieData['genre_ids']) && !empty($movieData['genre_ids'])) {
                        $categoryIds = [];
                        foreach ($movieData['genre_ids'] as $tmdbGenreId) {
                            if (isset($genreMap[$tmdbGenreId])) {
                                $genreName = $genreMap[$tmdbGenreId];
                                if (!empty($genreName)) {
                                    // Find category by slug, or create it with name if not found
                                    $category = Category::firstOrCreate(
                                        ['slug' => Str::slug($genreName)],
                                        ['name' => $genreName]
                                    );
                                    $categoryIds[] = $category->id;
                                }
                            }
                        }
                        // Attach genres using sync(). This replaces existing associations
                        // ensuring the movie's genres match exactly what the API provided.
                        if (!empty($categoryIds)) {
                            $movie->categories()->sync($categoryIds); 
                            $this->line("   -> Synced genres for: {$movie->title}");
                        }
                    }
                    // --- END GENRE SYNCING ---

                    $moviesAddedOrUpdated++;
                    $this->line(" -> Processed: {$movie->title}");

                } catch (Throwable $e) {
                    // Log error and continue with the next movie
                    // Prepare the movie title safely, defaulting to 'N/A' if null
                    $movieTitleForError = $movieData['title'] ?? 'N/A'; 

                    // Use the safe title variable in the error string
                    $this->error("Error processing movie ID {$movieData['id']} ('{$movieTitleForError}'): " . $e->getMessage());
                    continue; 
                }
            } // End foreach $moviesFromApi

             // Add a small delay between page requests to respect API rate limits
             if($page < $pagesToFetch) {
                 sleep(1); // Sleep for 1 second
             }

        } // End for $page

        $this->info("--------------------");
        $this->info("Population complete. Added or updated {$moviesAddedOrUpdated} movies.");
        return 0; // Indicate success
    }
}