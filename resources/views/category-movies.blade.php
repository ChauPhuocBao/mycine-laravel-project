@extends('layouts.layout')
@php use Illuminate\Support\Str; @endphp

@section('content')
<main>
    <div class="container" style="padding-top: 100px; padding-bottom: 50px;">
        <h1 class="text-center mb-2" style="color: var(--primary-color);">{{ $category->name }} Movies</h1>

        <!-- Sorting Controls -->
        <div class="d-flex justify-content-center justify-content-md-end mb-4">
             <div class="dropdown">
                <button class="btn btn-sm dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #333; color: #ccc;">
                    Sort by:
                    @if($currentSort === 'release_year_desc') Newest
                    @elseif($currentSort === 'release_year_asc') Oldest
                    @elseif($currentSort === 'rating_desc') Rating (High-Low)
                    @elseif($currentSort === 'rating_asc') Rating (Low-High)
                    @elseif($currentSort === 'title_asc') Title (A-Z)
                    @elseif($currentSort === 'title_desc') Title (Z-A)
                    @else Newest
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="sortDropdown" style="background-color: #222;">
                    {{-- Generate sort links preserving current page (pagination removes page if not needed) --}}
                    <li><a class="dropdown-item @if($currentSort === 'release_year_desc') active @endif" href="{{ request()->fullUrlWithQuery(['sort' => 'release_year_desc', 'page' => 1]) }}" style="color: #f8f9fa;">Newest First</a></li>
                    <li><a class="dropdown-item @if($currentSort === 'release_year_asc') active @endif" href="{{ request()->fullUrlWithQuery(['sort' => 'release_year_asc', 'page' => 1]) }}" style="color: #f8f9fa;">Oldest First</a></li>
                    <li><a class="dropdown-item @if($currentSort === 'rating_desc') active @endif" href="{{ request()->fullUrlWithQuery(['sort' => 'rating_desc', 'page' => 1]) }}" style="color: #f8f9fa;">Rating (High to Low)</a></li>
                    <li><a class="dropdown-item @if($currentSort === 'rating_asc') active @endif" href="{{ request()->fullUrlWithQuery(['sort' => 'rating_asc', 'page' => 1]) }}" style="color: #f8f9fa;">Rating (Low to High)</a></li>
                    <li><a class="dropdown-item @if($currentSort === 'title_asc') active @endif" href="{{ request()->fullUrlWithQuery(['sort' => 'title_asc', 'page' => 1]) }}" style="color: #f8f9fa;">Title (A-Z)</a></li>
                    <li><a class="dropdown-item @if($currentSort === 'title_desc') active @endif" href="{{ request()->fullUrlWithQuery(['sort' => 'title_desc', 'page' => 1]) }}" style="color: #f8f9fa;">Title (Z-A)</a></li>
                </ul>
            </div>
        </div>

        <hr style="color: #4e4e4e;">

        <!-- Movie Grid -->
        <div class="row mt-4 g-2">
            @forelse($movies as $movie)
                @include('partials._movie_card_overlay', ['movie' => $movie])
            @empty
                <div class="col-12">
                    <p class="text-center text-white-50">No movies found in this category yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $movies->links('pagination::bootstrap-5') }}
        </div>
    </div>
</main>
@endsection