@extends('layouts.layout')

@section('content')
<main>
    <div class="container" style="padding-top: 100px; padding-bottom: 50px; color: #f8f9fa;">

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

    </div>
</main>
@endsection