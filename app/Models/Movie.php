<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = [
        'tmdb_id',
        'title',
        'slug',
        'description',
        'release_year',
        'poster_url',
        'imdb_rating',
        'is_featured',
        'trailer_url',
    ];
    public function categories()
        {
            return $this->belongsToMany(Category::class);
        }
}
