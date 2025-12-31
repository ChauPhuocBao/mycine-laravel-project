@extends('layouts.layout')

@section('content')
<main>
    <div class="container" style="padding-top: 100px; padding-bottom: 50px; color: #f8f9fa;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="mb-4">Write a Review for: <span style="color: var(--primary-color);">{{ $movie->title }}</span></h1>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops! Something went wrong.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="card shadow-lg border-0 rounded-lg" style="background-color: #222; color: #f8f9fa;">
                    <div class="card-body p-4">
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $movie->id }}">

                            {{-- Tiêu đề Review --}}
                            <div class="mb-3">
                                <label for="title" class="form-label">Review Title</label>
                                <input type="text" class="form-control bg-dark text-white" id="title" name="title" value="{{ old('title') }}" required>
                                {{-- THÊM DÒNG NÀY --}}
                                @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Rating (1-10) --}}
                            <div class="mb-3">
                                <label for="rating" class="form-label">Your Rating (out of 10)</label>
                                <select class="form-select bg-dark text-white" name="rating" id="rating" required>
                                    <option value="" disabled selected>Select a rating...</option>
                                    @for ($i = 10; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                                {{-- THÊM DÒNG NÀY --}}
                                @error('rating') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Nội dung Review (Body) --}}
                            <div class="mb-3">
                                <label for="body" class="form-label">Your Review (Minimum 50 characters)</label>
                                <textarea class="form-control bg-dark text-white" id="body" name="body" rows="10" placeholder="Write your detailed review here..." required>{{ old('body') }}</textarea>
                                {{-- THÊM DÒNG NÀY --}}
                                @error('body') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary" style="background-color: var(--primary-color); border: none;">Post Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection