<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Category;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $interstellar_cat_ids = Category::whereIn('slug', ['adventure', 'drama', 'sci-fi'])->pluck('id');

        $interstellar = Movie::create([
            'title' => 'Interstellar',
            'description' => 'Earth faces disasters, with survival hinging on interstellar travel. A newfound wormhole offers an escape route...',
            'release_year' => 2014,
            'poster_url' => 'assets/img/intersellar.jpg',
            'imdb_rating' => 8.7,
            'imdb_url' => 'https://www.imdb.com/title/tt0816692/',
            'trailer_url' => 'zSWdZVtXT7E',
            'is_featured' => true
        ]);

        $interstellar->categories()->attach($interstellar_cat_ids);

        $jailer_cat_ids = Category::whereIn('slug', ['action', 'crime'])->pluck('id');

        $jailer = Movie::create([
            'title' => 'Jailer',
            'description' => "Retired jailer Muthuvel Pandian's son, a police commissioner, disappears...",
            'release_year' => 2023,
            'poster_url' => 'assets/img/jailer.png',
            'imdb_rating' => 7.2,
            'imdb_url' => 'https://www.imdb.com/title/tt11663228/',
            'trailer_url' => null,
            'is_featured' => true
        ]);

        $jailer->categories()->attach($jailer_cat_ids);

        $kushi_cat_ids = Category::whereIn('slug', ['romance', 'drama'])->pluck('id');

        $kushi = Movie::create([
            'title' => 'Kushi',
            'description' => "The movie's plot feels tailored for the 1980s and misses the mark today...",
            'release_year' => 2023,
            'poster_url' => 'assets/img/kushi.jpg',
            'imdb_rating' => 6.3,
            'imdb_url' => 'https://www.imdb.com/title/tt15380630/',
            'trailer_url' => null,
            'is_featured' => false
        ]);

        $kushi->categories()->attach($kushi_cat_ids);
    }
}