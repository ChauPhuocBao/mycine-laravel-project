<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewComment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'movie_review_id', 'body'];

    // Comment này thuộc về 1 User
    public function user() {
        return $this->belongsTo(User::class);
    }
    // Comment này thuộc về 1 MovieReview
    public function movieReview() {
        return $this->belongsTo(MovieReview::class);
    }
}
