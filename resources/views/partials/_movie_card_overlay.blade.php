{{-- This partial view expects a $movie variable --}}
<div class="col-lg-4 col-md-6 mb-4">
    <a href="{{ $movie->imdb_url ?? '#' }}" target="_blank" class="text-decoration-none">
        <div class="movie-card-overlay">
            <img src="{{ $movie->poster_url ?: 'https://via.placeholder.com/500x750?text=No+Poster' }}" class="movie-card-image" alt="{{ e($movie->title ?? 'Untitled') }}">
            <div class="movie-card-content">
                <h5 class="movie-card-title">{{ e($movie->title ?? 'Untitled') }}</h5>
                <div class="movie-card-info d-flex align-items-center mb-2">
                    <span class="me-3">{{ $movie->release_year ?? 'N/A' }}</span>
                    <img src="{{ asset('assets/img/imdb.png') }}" alt="imdb" height="15" class="me-1">
                    <span>{{ $movie->imdb_rating ?? 'N/A' }}</span>
                </div>
                {{-- Make sure Str is available or remove Str::limit --}}
                <p class="movie-card-description">{{ \Illuminate\Support\Str::limit($movie->description ?? 'No description.', 80) }}</p>
            </div>
        </div>
    </a>
</div>