@extends('layouts.layout')

@section('content')
<main>
    <div class="container" style="padding-top: 100px; padding-bottom: 50px; color: #f8f9fa;">
        <div class="row justify-content-center">
            <div class="col-md-9"> {{-- Cho cột rộng hơn --}}
                
                {{-- Thông tin phim --}}
                <div class="mb-3">
                    <a href="{{ route('movie.detail', $review->movie->slug) }}" class="text-muted text-decoration-none">&larr; Back to {{ $review->movie->title }}</a>
                </div>

                {{-- Tiêu đề và Tác giả Review --}}
                <h1 style="color: var(--primary-color);">{{ $review->title }}</h1>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-warning" style="font-size: 1.2rem;">
                        {{ $review->rating }}/10 <i class="fa-solid fa-star"></i>
                    </span>
                    <span class="text-muted">
                        By {{ $review->user->name }} &bull; {{ $review->created_at->format('M d, Y') }}
                    </span>
                </div>
                <hr style="color: #4e4e4e;">

                {{-- NỘI DUNG CHÍNH BÀI REVIEW (BLOG) --}}
                <div class="review-body mt-4 fs-5" style="line-height: 1.7;">
                    {!! nl2br(e($review->body)) !!} {{-- Hiển thị nội dung đầy đủ và giữ xuống dòng --}}
                </div>

                {{-- =================================== --}}
                {{-- PHẦN BÌNH LUẬN (COMMENTS) --}}
                {{-- =================================== --}}
                <hr style="color: #4e4e4e;" class="mt-5">
                <h3 class="mb-4">Comments ({{ $comments->total() }})</h3>

                {{-- Form viết bình luận (chỉ cho người đã đăng nhập) --}}
                @auth
                    <form action="{{ route('reviews.comments.store', $review) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control bg-dark text-white" name="body" rows="3" placeholder="Write a comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="background-color: var(--primary-color); border: none;">Post Comment</button>
                    </form>
                @else
                    <p><a href="{{ route('login') }}">Login</a> to post a comment.</p>
                @endauth

                {{-- Danh sách bình luận --}}
                <div class="comment-list">
                    @forelse ($comments as $comment)
                        <div class="card mb-3" style="background-color: #222; border: 1px solid #444;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-white">{{ $comment->user->name }}</strong>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="card-text text-white-50 mt-2 mb-0">{{ $comment->body }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-white-50">No comments yet.</p>
                    @endforelse
                </div>

                {{-- Phân trang cho bình luận --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $comments->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection