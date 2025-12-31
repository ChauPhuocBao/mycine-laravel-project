<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ReviewComment;

class MovieReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * (Các thuộc tính được phép gán hàng loạt)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'movie_id',
        'title',
        'body',
        'rating',
    ];

    /**
     * Bài review này thuộc về 1 User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Bài review này thuộc về 1 Movie
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Bài review này có nhiều Comment
     */
    public function comments()
    {
        return $this->hasMany(ReviewComment::class)->latest();
    }
}