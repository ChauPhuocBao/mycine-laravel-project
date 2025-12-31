<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie; // Import Movie model
use App\Models\MovieReview; // Import MovieReview model
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\ReviewComment;

class MovieReviewController extends Controller
{
    /**
     * Show the form for creating a new review.
     * Hiển thị form để viết review mới.
     *
     * @param string $slug The movie's slug
     * @return \Illuminate\Contracts\View\View
     */
    public function create(string $slug) // Nhận $slug từ URL
    {
        // Tìm phim bằng slug, nếu không thấy sẽ tự động 404
        $movie = Movie::where('slug', $slug)->firstOrFail(); 

        // Kiểm tra xem user đã review phim này chưa
        $existingReview = $movie->movieReviews()->where('user_id', Auth::id())->first();
        if ($existingReview) {
            // Nếu đã review, chuyển hướng về trang chi tiết và báo lỗi
            return redirect()->route('movie.detail', $movie->slug)
                             ->with('error', 'You have already reviewed this movie.');
        }

        // Trả về view 'reviews.create' và gửi biến $movie
        return view('reviews.create', ['movie' => $movie]); 
    }

    /**
     * Store a newly created review in storage.
     * Lưu bài review mới vào database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validate (kiểm tra) dữ liệu
        $data = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:50', // Yêu cầu nội dung tối thiểu 50 ký tự
            'rating' => 'required|integer|min:1|max:10', // Rating 1-10
        ]);

        // 2. Dùng updateOrCreate để tạo mới (hoặc cập nhật nếu đã lỡ có)
        $review = MovieReview::updateOrCreate(
            [
                'user_id' => Auth::id(), // ID của user đang đăng nhập
                'movie_id' => $data['movie_id']
            ],
            [
                'title' => $data['title'],
                'body' => $data['body'],
                'rating' => $data['rating']
            ]
        );

        // 3. Tìm lại phim để lấy slug
        $movie = Movie::findOrFail($data['movie_id']);
        
        // 4. Chuyển hướng về trang chi tiết phim với thông báo thành công
        return redirect()->route('movie.detail', $movie->slug)
                         ->with('success', 'Your review has been posted successfully!');
    }

    public function show(MovieReview $movieReview)
    {
        $movieReview->load('user', 'movie');
        $comments = $movieReview->comments()
                                ->with('user')
                                ->latest()
                                ->paginate(10);
        return view('reviews.show',[
            'review' => $movieReview,
            'comments' => $comments,
        ]);
    }

    public function storeComment(Request $request, MovieReview $movieReview)
    {
        $data = $request->validate([
            'body' => 'required|string|min:1|max:2000',
        ]);

        // Tạo bình luận mới, liên kết với bài review và user
        ReviewComment::create([
            'user_id' => Auth::id(),
            'movie_review_id' => $movieReview->id,
            'body' => $data['body'],
        ]);

        return back()->with('success', 'Comment posted!');
    }
}