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

    public function showCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $movies = $category->movies()->orderBy('release_year', 'desc')->paginate(12);
        return view('category-movies', [
            'category' => $category,
            'movies' => $movies,
        ]);
    }
}
