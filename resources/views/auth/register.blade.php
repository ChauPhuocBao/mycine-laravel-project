@extends('layouts.layout')
@section('content')

<main>
    <div class="container" style="padding-top: 120px; padding-bottom: 120px;">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg" style="background-color: #222; color: #f8f9fa;">
                    <div class="card-header border-0">
                        <h3 class="text-center font-weight-light my-4">{{ __('Register') }}</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-floating mb-3">
                                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Name" style="background-color: #333; color: white; border: 1px solid #555;" />
                                <label for="name" style="color: #ccc;">{{ __('Name') }}</label>
                                @error('name')
                                    <div class="mt-2 text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Email" style="background-color: #333; color: white; border: 1px solid #555;" />
                                <label for="email" style="color: #ccc;">{{ __('Email') }}</label>
                                @error('email')
                                    <div class="mt-2 text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Password" style="background-color: #333; color: white; border: 1px solid #555;" />
                                <label for="password" style="color: #ccc;">{{ __('Password') }}</label>
                                @error('password')
                                    <div class="mt-2 text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" style="background-color: #333; color: white; border: 1px solid #555;" />
                                <label for="password_confirmation" style="color: #ccc;">{{ __('Confirm Password') }}</label>
                                @error('password_confirmation')
                                    <div class="mt-2 text-sm text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid mt-4 mb-0">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>     
                    </div>
                    
                    <div class="card-footer text-center py-3 border-0">
                        <div class="small">
                            <a href="{{ route('login') }}">Already registered? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection