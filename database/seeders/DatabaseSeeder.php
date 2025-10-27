<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints();

        DB::table('category_movie')->truncate();

        DB::table('movies')->truncate();
        DB::table('categories')->truncate();
        Schema::enableForeignKeyConstraints();
        $this->call([
            CategorySeeder::class,
            MovieSeeder::class,
        ]);
    }
}