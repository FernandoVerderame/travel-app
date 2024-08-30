@extends('layouts.app')

@section('title', 'Trip Edit')

@section('content')

<header>
    <h1 class="my-2">Edita Viaggio</h1>
</header> 

@include('includes.trips.form')

@endsection

@section('scripts')
    @vite('resources/js/image_preview.js')
    @vite('resources/js/slug.js')
@endsection