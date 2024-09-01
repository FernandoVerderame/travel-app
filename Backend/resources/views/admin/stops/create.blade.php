@extends('layouts.app')

@section('title', 'Stop Create')

@section('content')

<header>
    <h1 class="my-2">Crea Tappa</h1>
</header> 

@include('includes.stops.form')

@endsection

@section('scripts')
    @vite('resources/js/image_preview.js')
    @vite('resources/js/slug.js')
    @vite('resources/js/address.js')
@endsection