@extends('layouts.app')

@section('title', $trip->title)

@section('content')

<header class="mt-3">
  {{-- Mostra il titolo del viaggio --}}
  <h1>{{$trip->title}}</h1>
</header>

<div class="row">
  <div class="col-12">
    <div class="">
        {{-- Sezione dell'itinerario del viaggio --}}
        <h3>Itinerario</h3>
        <div id="days-container">

            <div class="timeline">
                @foreach($days as $day)
                    <div class="timeline-day">
                        {{-- Header del giorno con data e numero --}}
                        <div class="timeline-header">
                            <h4 class="m-0">Giorno: {{ $day->number }}</h4>
                            <h5>{{ $day->date->format('d-m-Y') }}</h5>

                            <!-- Sezione previsioni meteo -->
                            @if($day->weather)
                                <div class="weather-info">
                                    <h6 class="m-0">Previsioni meteo:</h6>
                                    {{-- Mostra la temperatura e una descrizione meteo --}}
                                    <p class="m-0">Temperatura: <strong>{{ $day->weather['temperature'] }}°C</strong></p>
                                    <p>Descrizione: <strong>{{ ucfirst($day->weather['description']) }}</strong></p>
                                    {{-- Icona meteo
                                    <img src="http://www.weatherbit.io/static/img/icons/{{ $day->weather['icon'] }}.png" alt="Icona Meteo"> --}}
                                </div>
                            @else
                                {{-- Messaggio se non ci sono informazioni meteo --}}
                                <p>Nessuna informazione meteo disponibile.</p>
                        @endif
                        </div>
            
                        {{-- Ciclo attraverso le tappe del giorno --}}
                        @foreach($day->stops->sortBy('expected_duration') as $stop)
                            <div class="timeline-item">
                                {{-- Linea verticale che collega le tappe --}}
                                <div class="timeline-line"></div>
            
                                {{-- Icona della tappa --}}
                                <div class="timeline-icon">
                                    @if ($stop->image)
                                        <img src="{{ Vite::asset('public/storage/' . $stop->image) }}" alt="{{ $stop->title }}" class="rounded-circle">
                                    @else
                                        <img src="https://via.placeholder.com/50" alt="placeholder-image" class="rounded-circle">
                                    @endif
                                </div>
            
                                {{-- Contenuto della tappa --}}
                                <div class="timeline-content">
                                    <h5>{{ $stop->title }}</h5>
                                    <p><i class="fa-solid fa-clock me-2"></i><strong>Orario:</strong> {{ $stop->expected_duration ? $stop->expected_duration : 'Da stabilire' }}</p>
                                    <p><i class="fa-solid fa-map-marker-alt me-2"></i><strong>Luogo:</strong> {{ $stop->address }}</p>
                                    <p><i class="fa-solid fa-utensils me-2"></i><strong>Piatti tipici:</strong> {{ $stop->foods }}</p>
                                    <p>
                                        {{-- Mostra la valutazione della tappa con le stelle --}}
                                        <div class="star-rating">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <i class="fa-solid fa-star fs-5 {{ $i <= $stop->rating ? 'filled' : 'empty' }}" title="{{ $i }} star"></i>
                                            @endfor
                                        </div>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            
        </div>
    </div>
  </div>
</div>

{{-- Pulsante per tornare alla lista dei viaggi --}}
<div class="d-flex justify-content-between">
  <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-rotate-left me-2"></i>Indietro
  </a>
</div>

@endsection

@section('scripts')
    {{-- Inclusione degli script per la conferma di cancellazione e per i tooltip --}}
    @vite('resources/js/delete_confirmation.js')
    @vite('resources/js/tooltip.js')
@endsection