@extends('layouts.home')

@section('title', 'Home')

@section('content')

<section id="home-sec">
    {{-- Titolo principale della pagina --}}
    <div>
        <h1>Vivi il mondo, uno step alla volta.</h1>
        <h3>Esplora. Sogna. Scopri</h3>
    </div>

    {{-- Pulsanti per Login e Registrazione --}}
    <div class="btns d-flex gap-3">
        <a href="{{ route('login') }}" type="button" class="btn btn-light">Login</a>
        <span>|</span>
        <a href="{{ route('register') }}" type="button" class="btn btn-light">Register</a>
    </div>

    {{-- Descrizione dell'app --}}
    <p>Scopri nuovi posti, salva i tuoi ricordi e condividi le tue esperienze con gli amici. <br> Pianifica e documenta i tuoi viaggi con la nostra app. </p>
</section>

@endsection