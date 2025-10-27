<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Share categories data with the layout file
        View::composer('layouts.layout', function ($view) {
            // Fetch all categories from the database, ordered by name
            $categories = Category::orderBy('name')->get();
            // Pass the categories to the view
            $view->with('all_categories', $categories);
        });
    }
}
