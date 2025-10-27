<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache; // Import Cache facade
use App\Models\Movie;
use App\Models\Category; // Import Category model
use Illuminate\Support\Str;
use Throwable; // Import Throwable for error handling

class FetchTrendingMovies extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'movies:fetch-trending';

    /**
     * The console command description.
     */
    protected $description = 'Fetch trending movies, trailers, AND genres from TMDb and update database';

    /**
     * Fetch the genre mapping from TMDb or cache.
     * Caches the result for 1 day to minimize API calls.
     * @return array Map of TMDb genre ID to genre name.
     */
    private function getGenreMap(): array
    {
        // Try to get from cache first, cache for 1 day (86400 seconds)
        return Cache::remember('tmdb_genre_map', 86400, function () {
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
        $this->info('Starting to fetch trending movies from TMDb...');

        $apiKey = config('services.tmdb.api_key');
        if (!$apiKey) {
            $this->error('Error: TMDB_API_KEY is not configured.');
            return 1;
        }

        // --- Get Genre Map ---
        $genreMap = $this->getGenreMap(); // Call the helper method
        if (empty($genreMap)) {
            $this->warn('Warning: Could not retrieve genre map. Genre syncing will be skipped.');
            // Continue execution, just skip genre sync
        }


        // --- Get Trending Movies ---
        $trendingResponse = Http::get("https://api.themoviedb.org/3/trending/movie/week?api_key={$apiKey}&language=en-US");

        if ($trendingResponse->failed()) {
            $this->error('Failed to connect to TMDb API (trending).');
            return 1;
        }

        $moviesFromApi = $trendingResponse->json()['results'] ?? []; // Add ?? [] safety

        if (empty($moviesFromApi)) {
             $this->info('No trending movies returned from API.');
             return 0; // Exit successfully if no movies
        }


        // --- Reset Featured Flag ---
        $this->info('Resetting is_featured flag for all movies...');
        Movie::query()->update(['is_featured' => false]);

        // --- Process Top 3 Movies ---
        $featuredMoviesData = array_slice($moviesFromApi, 0, 3);
        $this->info('Got top 3 movies. Updating database, fetching trailers, and syncing genres...');

        foreach ($featuredMoviesData as $movieData) {
            try {
                // Skip if essential data is missing
                 if (empty($movieData['id']) || empty($movieData['title'])) {
                    $this->warn("Skipping trending movie with missing ID or Title.");
                    continue;
                 }

                // --- Update or Create Movie ---
                $movie = Movie::updateOrCreate(
                    ['tmdb_id' => $movieData['id']],
                    [
                        'title' => $movieData['title'],
                        // Create unique slug for trending movies too
                        'slug' => Str::slug($movieData['title']) . '-' . $movieData['id'],
                        'description' => $movieData['overview'] ?? '',
                        'release_year' => isset($movieData['release_date']) && strlen($movieData['release_date']) >= 4 ? Str::substr($movieData['release_date'], 0, 4) : null,
                        'poster_url' => isset($movieData['backdrop_path']) ? 'https://image.tmdb.org/t/p/original' . $movieData['backdrop_path'] : null, // Using backdrop
                        'imdb_rating' => isset($movieData['vote_average']) ? round($movieData['vote_average'], 1) : null,
                        'is_featured' => true, // Set as featured
                        'trailer_url' => null, // Reset trailer, fetch below
                    ]
                );

                $this->info("Updated/Created: {$movie->title} (TMDB ID: {$movie->tmdb_id})");

                // --- Fetch Trailer ---
                $this->info(" -> Fetching videos for {$movie->title}...");
                $videosResponse = Http::get("https://api.themoviedb.org/3/movie/{$movie->tmdb_id}/videos?api_key={$apiKey}");

                if ($videosResponse->successful()) {
                    $videos = $videosResponse->json()['results'] ?? []; // Add ?? [] safety
                    $trailerKey = null;

                    // Find the official YouTube trailer
                    foreach ($videos as $video) {
                        // Check if keys exist before accessing
                        if (isset($video['site'], $video['type'], $video['key']) &&
                            strtolower($video['site']) === 'youtube' &&
                            strtolower($video['type']) === 'trailer')
                        {
                            $trailerKey = $video['key'];
                            // Prefer official trailers if available
                            if (isset($video['official']) && $video['official'] === true) {
                                break; // Found the official one
                            }
                        }
                    }

                    if ($trailerKey) {
                        $movie->update(['trailer_url' => $trailerKey]);
                        $this->info(" -> Trailer found and updated: {$trailerKey}");
                    } else {
                        $this->warn(" -> No YouTube trailer found for {$movie->title}.");
                    }
                } else {
                    $this->warn(" -> Failed to fetch videos for {$movie->title}.");
                }

                // --- START GENRE SYNCING ---
                // Only sync if genreMap was loaded successfully
                if (!empty($genreMap) && isset($movieData['genre_ids']) && !empty($movieData['genre_ids'])) {
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
                    // Attach genres using sync().
                    if (!empty($categoryIds)) {
                        $movie->categories()->sync($categoryIds);
                        $this->line("   -> Synced genres for: {$movie->title}");
                    }
                }
                // --- END GENRE SYNCING ---


            } catch (Throwable $e) {
                // Log error and continue with the next movie
                $movieTitleForError = $movieData['title'] ?? 'N/A'; // Safe title access
                $this->error("Error processing trending movie ID {$movieData['id']} ('{$movieTitleForError}'): " . $e->getMessage());
                continue;
            }
        } // End foreach loop

        $this->info('Complete! Carousel updated with trending movies, trailers, and genres.');
        return 0; // Indicate success
    }
}