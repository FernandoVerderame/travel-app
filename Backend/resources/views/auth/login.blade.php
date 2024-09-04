@extends('layouts.login-register')

@section('title', 'Login')

@section('content')

    <div class="container p-0">
        <div class="row justify-content-center login-sec">
            <div class="col-md-8">
                <div class="card border-0">
                    <div class="card-header d-flex justify-content-center align-items-center gap-3">
                        <div>{{ config('app.name', 'Travel App') }}</div>
                        <img src="{{ Vite::asset('resources/images/logo.png') }}" class="img-logo" alt="logo-travel-app">
                        <div>{{ __('Login') }}</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-6 p-0 d-flex justify-content-center align-items-center form-column">
                                <form method="POST" action="{{ route('login') }}" class="d-flex flex-column gap-3">
                                    @csrf
            
                                    <div class="mb-4 row">
                                        <label for="email" class="col-md-12 col-form-label text-md-right d-flex justify-content-center text-white">{{ __('E-Mail') }}</label>
            
                                        <div class="col-md-12">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
            
                                    <div class="mb-4 row">
                                        <label for="password" class="col-md-12 col-form-label text-md-right d-flex justify-content-center text-white">{{ __('Password') }}</label>
            
                                        <div class="col-md-12">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
            
                                    <div class="mb-4 row">
                                        <div class="col-md-6 col-md-12 d-flex justify-content-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            
                                                <label class="form-check-label text-white" for="remember">
                                                    {{ __('Ricordami') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="mb-4 row mb-0">
                                        <div class="col-md-12 col-md-4 d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Login') }}
                                            </button>
            
                                            @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Password dimenticata?') }}
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-6">
                                <div class="bg-login-form">
                                    <p class="phrase">Colleziona i tuoi ricordi con <br> un semplice click!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
