<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MovieReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/category/{slug}', [PageController::class, 'showCategory'])->name('category.show');
Route::get('/movie/{slug}', [PageController::class, 'showMovieDetails'])->name('movie.detail');
Route::get('/search', [PageController::class, 'search'])->name('movie.search');




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->group(function () {
    // Route để hiển thị form VIẾT review mới
    Route::get('/movie/{slug}/review/create', [MovieReviewController::class, 'create'])->name('reviews.create');
    // Route để LƯU review mới
    Route::post('/reviews', [MovieReviewController::class, 'store'])->name('reviews.store');

    // Route để LƯU BÌNH LUẬN cho một review
    Route::post('/review/{movieReview}/comment', [MovieReviewController::class, 'storeComment'])->name('reviews.comments.store');
});
Route::get('/review/{movieReview}', [MovieReviewController::class, 'show'])->name('reviews.show');

require __DIR__.'/auth.php';