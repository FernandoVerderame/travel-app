@extends('layouts.app')

@section('title', 'Admin Home')

@section('content')
<div class="container">
    <h2 class="fs-4 text-secondary my-4">
        {{ __('Dashboard') }}
    </h2>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card p-2">
                
                <div id="map" style="height: 500px; width: 100%;"></div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @vite('resources/js/map.js')

    <script>
        const locations = @json($locations);
    </script>

    <!-- API Google Maps -->
    <script defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('VITE_GOOGLE_MAPS_API_KEY') }}&callback=initMap&libraries=marker">
    </script>
@endsection