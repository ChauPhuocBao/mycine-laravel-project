<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->text('description')->nullable(); 
            $table->smallInteger('release_year')->nullable(); 
            $table->string('poster_url')->nullable(); 
            $table->decimal('imdb_rating', 3, 1)->nullable(); 
            $table->string('imdb_url')->nullable(); 
            $table->string('trailer_url')->nullable(); 
            $table->boolean('is_featured')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
};
