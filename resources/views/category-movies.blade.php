@extends('layouts.layout')

@php use Illuminate\Support\Str; @endphp

@section('content')

<main>
    <div class="container" style="padding-top: 100px; padding-bottom: 50px;">
        {{-- Hiển thị tên thể loại --}}
        <h1 class="text-center mb-4" style="color: var(--primary-color);">{{ $category->name }} Movies</h1>
        <hr style="color: #4e4e4e;">

        {{-- Hiển thị danh sách phim (dùng lại hàm render card) --}}
        <div class="row mt-4">
            @forelse($movies as $movie)
                {{-- Gọi lại hàm render card từ home.blade.php hoặc tạo partial --}}
                {{-- Tạm thời copy lại cấu trúc card overlay ở đây --}}
                <div class="col-lg-3 col-md-4 mb-4"> {{-- Dùng col-lg-3 để hiển thị 4 phim/hàng --}}
                    <a href="{{ $movie->imdb_url ?? '#' }}" target="_blank" class="text-decoration-none">
                        <div class="movie-card-overlay" style="height: auto; aspect-ratio: 2 / 3;"> {{-- Dùng aspect-ratio thay height cố định --}}
                            <img src="{{ $movie->poster_url ?: 'https://via.placeholder.com/500x750?text=No+Poster' }}" class="movie-card-image" alt="{{ e($movie->title ?? 'Untitled') }}">
                            <div class="movie-card-content">
                                <h5 class="movie-card-title">{{ e($movie->title ?? 'Untitled') }}</h5>
                                <div class="movie-card-info d-flex align-items-center mb-2">
                                    <span class="me-3">{{ $movie->release_year ?? 'N/A' }}</span>
                                    <img src="{{ asset('assets/img/imdb.png') }}" alt="imdb" height="15" class="me-1">
                                    <span>{{ $movie->imdb_rating ?? 'N/A' }}</span>
                                </div>
                                <p class="movie-card-description">{{ Str::limit($movie->description ?? 'No description.', 80) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-white-50">No movies found in this category yet.</p>
                </div>
            @endforelse
        </div>

        {{-- Hiển thị nút phân trang --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $movies->links('pagination::bootstrap-5') }}
        </div>

    </div>
</main>

@endsection