@extends('layouts.layout')
@section('content')
<main>
    <div class="container" style="padding-top: 120px; padding-bottom: 120px;">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg" style="background-color: #222; color: #f8f9fa;">
                    <div class="card-header border-0">
                        <h3 class="text-center font-weight-light my-4">{{ __('Login') }}</h3>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-floating mb-3">

                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Email" style="background-color: #333; color: white; border: 1px solid #555;" />

                                <label for="email" style="color: #ccc;">{{ __('Email address') }}</label>

                                @error('email')
                                    <div class="mt-2 text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating mt-4 mb-3">
                                <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Password" style="background-color: #333; color: white; border: 1px solid #555;" />

                                <label for="password" style="color: #ccc;">{{ __('Password') }}</label>
                                @error('password')
                                    <div class="mt-2 text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-4">

                                <div class="form-check">
                                    <input class="form-check-input" id="remember_me" type="checkbox" name="remember" style="background-color: #333; border: 1px solid #555;">
                                    <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="small" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="d-grid mt-4 mb-0">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Log in') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3 border-0">
                        <div class="small">
                            <a href="{{ route('register') }}">Need an account? Sign up!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection