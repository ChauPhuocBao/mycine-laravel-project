<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;

class PageController extends Controller
{

    public function home()
    {

        $featured_movies = Movie::where('is_featured', true)
                                ->with('categories')
                                ->take(3)
                                ->get();

        // --- Fetch Movies For Each Category Section ---

        // Helper function (Keep as is)
        $getMoviesByCategory = function (string $slug, int $limit = 10): Collection {
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                return $category->movies()
                                ->orderBy('release_year', 'desc')
                                ->take($limit)
                                ->get();
            }
            return collect();
        };
        // Fetch movies for the requested categories
        $action_movies = $getMoviesByCategory('action', 9);
        $horror_movies = $getMoviesByCategory('horror', 9);
        $adventure_movies = $getMoviesByCategory('adventure', 9);
        $romance_movies = $getMoviesByCategory('romance', 9);
        $mystery_movies = $getMoviesByCategory('mystery', 9);
        $scifi_movies = $getMoviesByCategory('science-fiction', 9);

        // --- Return the View with Data ---
        return view('home', [
            'featured_movies' => $featured_movies,
            'action_movies'   => $action_movies,
            'horror_movies'   => $horror_movies,
            'adventure_movies'=> $adventure_movies,
            'romance_movies'  => $romance_movies,
            'mystery_movies'  => $mystery_movies,
            'scifi_movies'    => $scifi_movies,
        ]);
    }

    public function showCategory(string $slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Sorting
        $sort = $request->query('sort', 'release_year_desc');   //Default: Release year desc
        $sortColumn = 'release_year';
        $sortDirection = 'desc';

        if ($sort === 'rating_desc') {
            $sortColumn = 'imdb_rating';
            $sortDirection = 'desc';
        } elseif ($sort === 'rating_asc') {
            $sortColumn = 'imdb_rating';
            $sortDirection = 'asc';
        } elseif ($sort === 'title_asc') {
            $sortColumn = 'title';
            $sortDirection = 'asc';
        } elseif ($sort === 'title_desc') {
            $sortColumn = 'title';
            $sortDirection = 'desc';
        } elseif ($sort === 'release_year_asc') {
            $sortColumn = 'release_year';
            $sortDirection = 'asc';
        }
        $movies = $category->movies()
                            ->orderBy($sortColumn, $sortDirection)
                            ->paginate(12)
                            ->withQueryString();

        return view('category-movies', [
            'category' => $category,
            'movies' => $movies,
            'currentSort' => $sort,
        ]);
    }

    public function showMovieDetails(string $slug)
    {
        $movie = Movie::where('slug', $slug)->firstOrFail();

        return view('movie_details',
        [
            'movie' => $movie,
        ]);
    }
    public function search(Request $request)
    {
        // Get the search query from the request input
        $searchQuery = $request->input('query');

        // Search in the database where title or description contains the search query
        $movies = Movie::where('title', 'LIKE', "%{$searchQuery}%")
                       ->orWhere('description', 'LIKE', "%{$searchQuery}%")
                       ->orderBy('release_year', 'desc') // Optional: sort results
                       ->paginate(12) // Paginate the results
                       ->withQueryString(); // Append the query string to pagination links

        // Return a view with the search results and the original query
        return view('search-results', [
            'movies' => $movies,
            'searchQuery' => $searchQuery,
        ]);
    }
}
