@extends('layouts.layout')

@php use Illuminate\Support\Str; @endphp

@section('content')
<main>
    <div class="container" style="padding-top: 100px; padding-bottom: 50px; color: #f8f9fa;">

        {{-- =================================== --}}
        {{-- MOVIE DETAILS SECTION --}}
        {{-- =================================== --}}
        <div class="row">
            <div class="col-md-4 text-center">
                {{-- Display Poster --}}
                <img src="{{ $movie->poster_url ?: 'https://via.placeholder.com/500x750?text=No+Poster' }}"
                     alt="{{ e($movie->title) }}"
                     class="img-fluid rounded mb-3"
                     style="max-width: 350px;">
            </div>
            <div class="col-md-8">
                {{-- Display Title --}}
                <h1 style="color: var(--primary-color);">{{ $movie->title }}</h1>
                {{-- Display Year and Rating --}}
                <p class="text-muted">
                    {{ $movie->release_year }}
                    <img src="{{ asset('assets/img/imdb.png') }}" alt="imdb" height="18" class="ms-3 me-1">
                    {{ $movie->imdb_rating ?? 'N/A' }}
                </p>

                {{-- Display Genres --}}
                @if($movie->categories->isNotEmpty())
                <p>
                    <strong>Genres:</strong>
                    @foreach($movie->categories as $category)
                        <a href="{{ route('category.show', ['slug' => $category->slug]) }}" class="badge bg-secondary text-decoration-none me-1">{{ $category->name }}</a>
                    @endforeach
                </p>
                @endif

                <hr style="color: #4e4e4e;">

                {{-- Display Description --}}
                <h5 class="mt-4">Description</h5>
                <p>{{ $movie->description ?? 'No description available.' }}</p>

                {{-- Display Trailer (if available) --}}
                @if($movie->trailer_url)
                <h5 class="mt-4">Trailer</h5>
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/{{ $movie->trailer_url }}"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                </div>
                @endif

                {{-- Link to IMDb --}}
                 @if($movie->imdb_url)
                    <a href="{{ $movie->imdb_url }}" target="_blank" class="btn btn-outline-warning mt-4">
                        View on IMDb
                    </a>
                 @endif
            </div>
        </div>
        {{-- =================================== --}}
        {{-- END MOVIE DETAILS SECTION --}}
        {{-- =================================== --}}


        {{-- =================================== --}}
        {{-- USER REVIEWS (BLOGS) SECTION --}}
        {{-- =================================== --}}
        <hr style="color: #4e4e4e;" class="mt-5">

        <div class="row mt-4">
            <div class="col-md-12">
                
                {{-- Section Title and Write Review Button --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">User Reviews ({{ $movie->movieReviews->count() }})</h3>
                    
                    {{-- "Write a Review" button (Logged-in users only) --}}
                    @auth
                        <a href="{{ route('reviews.create', ['slug' => $movie->slug]) }}" class="btn btn-primary" style="background-color: var(--primary-color); border: none;">
                            <i class="fa-solid fa-pen-to-square"></i> Write a Review
                        </a>
                    @endauth
                    {{-- Prompt for guests --}}
                    @guest
                         <p class="mb-0"><a href="{{ route('login') }}">Login</a> to write a review.</p>
                    @endguest
                </div>
                
                {{-- Loop to display review list --}}
                @forelse ($movie->movieReviews as $review)
                    <div class="card mb-3" style="background-color: #222; border: 1px solid #444;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    {{-- Review Title (Link to full review page later) --}}
                                    <h4 class="card-title mb-1" style="color: var(--primary-color);">
                                        <a href="{{ route('reviews.show', $review) }}" class="text-decoration-none" style="color: var(--primary-color);">{{ e($review->title) }}</a>
                                    </h4>
                                    {{-- Rating --}}
                                    <h6 class="card-subtitle mb-2 text-warning">
                                        {{ $review->rating }}/10 <i class="fa-solid fa-star"></i>
                                    </h6>
                                </div>
                                {{-- Author and Date --}}
                                <div class="text-end text-muted">
                                    <small>by {{ $review->user->name }}</small><br>
                                    <small>{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            
                            {{-- Review Body Snippet --}}
                            <p class="card-text text-white-50 mt-3">
                                {{ Str::limit($review->body, 300) }}
                                <a href="{{ route('reviews.show', $review) }}" class="text-white-50">(Read More...)</a>
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-white-50">No reviews yet for this movie.</p>
                @endforelse

                {{-- Pagination Links (if you paginate reviews in Controller) --}}
                {{-- <div class="d-flex justify-content-center mt-4"> --}}
                {{--    {{ $movie->movieReviews->links('pagination::bootstrap-5') }} --}}
                {{-- </div> --}}
            </div>
        </div>
        {{-- =================================== --}}
        {{-- END USER REVIEWS SECTION --}}
        {{-- =================================== --}}

    </div> {{-- End .container --}}
</main>
@endsection