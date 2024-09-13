@extends('layouts.app')

@section('title', 'Admin Home')

@section('content')
<div class="container">
    {{-- Titolo della dashboard --}}
    <h2 class="fs-4 text-secondary">
        {{ __('Dashboard') }}
    </h2>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card p-2">
                {{-- Sezione che contiene la mappa, con altezza e larghezza definite --}}
                <div id="map" style="height: 600px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- Importa il file JavaScript che contiene la logica per l'inizializzazione della mappa --}}
    @vite('resources/js/map.js')

    <script>
        {{-- Passa le coordinate delle località alla variabile JavaScript `locations` --}}
        const locations = @json($locations);
    </script>

    {{-- Inclusione del Google Maps API script con la tua chiave API (presa dall'ambiente) e callback per inizializzare la mappa --}}
    <script defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('VITE_GOOGLE_MAPS_API_KEY') }}&callback=initMap&libraries=marker">
    </script>
@endsection