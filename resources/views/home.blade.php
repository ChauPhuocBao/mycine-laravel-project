@extends('layouts.layout')
@section('content')

<main>
    <section id="home">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @if($featured_movies && $featured_movies->count() > 0)           
                    @foreach($featured_movies as $index => $movie)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-bs-interval="5000">                    
                            <img src="{{ $movie->poster_url ? asset($movie->poster_url) : 'https://via.placeholder.com/1200x500?text=No+Image' }}" class="img" alt="{{ $movie->title }}">
                            
                            <div class="carousel-caption">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="home-carousel">
                                            <div class="gen-tag-line"><span>Featured</span></div>
                                            <div class="gen-movie-info mt-3">
                                                <h3 class="trending_title">{{ $movie->title ?? 'Untitled' }}</h3>
                                            </div>
                                            <div class="gen-meta-after-title">
                                                <img src="{{ asset('assets/img/imdb.png') }}" alt="imdb">
                                                <span class="ms-1">{{ $movie->imdb_rating ?? 'N/A' }}</span>
                                            </div>
                                            <div class="gen-meta-desc">
                                                <p class="description">{{ $movie->description ?? 'No description available.' }}</p>
                                            </div>
                                            <div class="gen-meta-info mt-4">
                                                <ul class="gen-meta-after-excerpt">
                                                    @if($movie->categories && $movie->categories->count() > 0)
                                                        <li class="genre">
                                                            <strong>Genre :</strong>
                                                            @foreach($movie->categories as $category)
                                                                <span>
                                                                    {{ $category->name }}{{ $loop->last ? '' : ', ' }}
                                                                </span>
                                                            @endforeach
                                                        </li>
                                                    @endif

                                                </ul>
                                            </div>
                                            <div class="gen-meta-btn mt-4">
                                                <a href="{{ $movie->imdb_url ?? '#' }}" target="_blank">
                                                    <button type="button"><i class="fa-solid fa-circle-info"></i>
                                                        &nbsp;View Details</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 home-right-video">
                                        @if($movie->trailer_url)
                                            <iframe width="550" height="350"
                                                src="https://www.youtube.com/embed/{{ $movie->trailer_url }}"
                                                title="YouTube video player" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                allowfullscreen></iframe>
                                        @else
                                            <div style="width: 550px; height: 350px; background: #333; color: white; display: flex; align-items: center; justify-content: center; font-family: sans-serif;">
                                                Trailer Not Available
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                
                @else
                    <div class="carousel-item active">
                        <img src="https://via.placeholder.com/1200x500?text=No+Featured+Movies" class="img" alt="No Movies">
                        <div class="carousel-caption d-block">
                            <h2 class="text-white">There is no feature movies</h2>
                        </div>
                    </div>
                @endif

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

        </div>
    </section>    
    
    <!-- Action -->
    <section class="action-movies" id="action">
        <div class="container">
            <h2 class="text-center">Action Movies</h2><hr>
            <div class="row mt-4 g-1">
                @forelse($action_movies as $movie)
                    @include('partials._movie_card_overlay', ['movie' => $movie])
                @empty
                    <div class="col-12"><p class="text-center text-white-50">No action movies found.</p></div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Horror -->
    <section class="horror-movies" id="horror">
        <div class="container">
            <h2 class="text-center">Horror Movies</h2><hr>
            <div class="row mt-4 g-1">
                @forelse($horror_movies as $movie)
                    @include('partials._movie_card_overlay', ['movie' => $movie])
                @empty
                    <div class="col-12"><p class="text-center text-white-50">No horror movies found.</p></div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Adventure -->
    <section class="adventure-movies" id="adventure">
         <div class="container">
            <h2 class="text-center">Adventure Movies</h2><hr>
            <div class="row mt-4 g-1">
                @forelse($adventure_movies as $movie)
                    @include('partials._movie_card_overlay', ['movie' => $movie])
                @empty
                    <div class="col-12"><p class="text-center text-white-50">No adventure movies found.</p></div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Romance -->
    <section class="romance-movies" id="romance">
        <div class="container">
            <h2 class="text-center">Romance Movies</h2><hr>
            <div class="row mt-4 g-1">
                @forelse($romance_movies as $movie)
                    @include('partials._movie_card_overlay', ['movie' => $movie])
                @empty
                     <div class="col-12"><p class="text-center text-white-50">No romance movies found.</p></div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Mysteries -->
    <section class="mystery-movies" id="mystery">
         <div class="container">
            <h2 class="text-center">Mystery Movies</h2><hr>
            <div class="row mt-4 g-1">
                @forelse($mystery_movies as $movie)
                    @include('partials._movie_card_overlay', ['movie' => $movie])
                @empty
                    <div class="col-12"><p class="text-center text-white-50">No mystery movies found.</p></div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Sci-fi -->
    <section class="scifi-movies" id="sci-fi">
         <div class="container">
            <h2 class="text-center">Science Fiction Movies</h2><hr>
            <div class="row mt-4 g-1">
                @forelse($scifi_movies as $movie)
                    @include('partials._movie_card_overlay', ['movie' => $movie])
                @empty
                    <div class="col-12"><p class="text-center text-white-50">No science fiction movies found.</p></div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Scroll Top -->
    <a href="#top" class="scroll-top btn-hover">
        <i class="fa-solid fa-chevron-up up-arrow"></i>
    </a>

</main>

@endsection