@extends('layouts.login-register')

@section('title', 'Register')

@section('content')

<div class="container p-0">
    <div class="row justify-content-center login-sec">
        <div class="col-md-8">
            <div class="card border-0">
                <div class="card-header d-flex justify-content-center align-items-center gap-3">
                    {{-- Nome dell'applicazione e logo --}}
                    <div>{{ config('app.name', 'Travel App') }}</div>
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" class="img-logo" alt="logo-travel-app">
                    <div>{{ __('Register') }}</div>
                </div>

                <div class="card-body p-0">
                    <div class="row">
                        {{-- Form di registrazione --}}
                        <div class="col-6 p-0 d-flex justify-content-center align-items-center form-column">
                            <form method="POST" action="{{ route('register') }}" class="d-flex flex-column gap-2">
                                @csrf
        
                                {{-- Campo Nome --}}
                                <div class="mb-4 row">
                                    <label for="name" class="col-md-12 col-form-label text-md-right d-flex justify-content-center text-white">{{ __('Nome') }}</label>
        
                                    <div class="col-md-12">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
        
                                {{-- Campo Email --}}
                                <div class="mb-4 row">
                                    <label for="email" class="col-md-12 col-form-label text-md-right d-flex justify-content-center text-white">{{ __('E-Mail') }}*</label>
        
                                    <div class="col-md-12">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
        
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
        
                                {{-- Campo Password --}}
                                <div class="mb-4 row">
                                    <label for="password" class="col-md-12 col-form-label text-md-right d-flex justify-content-center text-white">{{ __('Password') }}*</label>
        
                                    <div class="col-md-12">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
        
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
        
                                {{-- Campo Conferma Password --}}
                                <div class="mb-4 row">
                                    <label for="password-confirm" class="col-md-12 col-form-label text-md-right d-flex justify-content-center text-white">{{ __('Confirm Password') }}</label>
        
                                    <div class="col-md-12">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
        
                                {{-- Pulsante di registrazione --}}
                                <div class="mb-4 row mb-0">
                                    <div class="col-md-12 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- Sezione di benvenuto con frase promozionale --}}
                        <div class="col-6">
                            <div class="bg-register-form">
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