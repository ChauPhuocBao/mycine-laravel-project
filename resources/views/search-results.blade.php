{{-- resources/views/search-results.blade.php --}}

@extends('layouts.layout')

@section('content')
<main>
    <div class="container" style="padding-top: 100px; padding-bottom: 50px;">
        <h1 class="text-center mb-4" style="color: var(--primary-color);">
            Search Results for: <span class="text-white">"{{ e($searchQuery) }}"</span>
        </h1>

        <hr style="color: #4e4e4e;">

         <!-- Movie Grid -->
        <div class="row mt-4 g-2">
            @forelse($movies as $movie)
                 <!-- Re-use the same movie card partial -->
                @include('partials._movie_card_overlay', ['movie' => $movie])
            @empty
                <div class="col-12 text-center mt-5">
                    <p class="text-white-50 fs-4">
                        <i class="fa-solid fa-film fa-2x mb-3"></i><br>
                        Sorry, no movies were found matching your search.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($movies->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $movies->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</main>
@endsection