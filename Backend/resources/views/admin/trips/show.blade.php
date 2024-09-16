@extends('layouts.app')

@section('title', $trip->title)

@section('content')

<header class="mt-3 trip-header">
  {{-- Mostra il titolo del viaggio --}}
  <h1>{{$trip->title}}</h1>
</header>

<div class="row">
  <div class="col-12">
    <div class="">
        {{-- Sezione dell'itinerario del viaggio --}}
        <h3 class="m-0 mt-4">Itinerario</h3>
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
                                    @if ($stop->category)
                                        <div class="category-logo" title="{{ $stop->category->label }}">
                                            <i class="fa-solid {{ $stop->category->icon }}"></i>
                                        </div>
                                    @else
                                        <img src="https://via.placeholder.com/50" alt="placeholder-image" class="rounded-circle">
                                    @endif
                                </div>
            
                                {{-- Contenuto della tappa --}}
                                <div class="timeline-content row">
                                    <div class="col-md-4">
                                        <h5>{{ $stop->title }}</h5>
                                        <p><i class="fa-solid fa-clock me-2"></i><strong>Orario:</strong> {{ $stop->expected_duration ? $stop->expected_duration : 'Da stabilire' }}</p>
                                        <p><i class="fa-solid fa-map-marker-alt me-2"></i><strong>Luogo:</strong> {{ $stop->address }}</p>
                                        <p><i class="fa-solid fa-utensils me-2"></i><strong>Cibo:</strong> {{ $stop->foods }}</p>
                                        {{-- Mostra la valutazione della tappa con le stelle --}}
                                        <div class="star-rating">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <i class="fa-solid fa-star fs-5 {{ $i <= $stop->rating ? 'filled' : 'empty' }}" title="{{ $i }} star"></i>
                                            @endfor
                                        </div>

                                        {{-- Bottoni per la modifica e l'eliminazione --}}
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.stops.edit', ['trip' => $trip->slug, 'day' => $day->slug, 'stop' => $stop->slug]) }}" class="btn btn-warning me-2">
                                            <i class="fa-solid fa-edit"></i> Modifica
                                            </a>
                                            <form action="{{ route('admin.stops.destroy', $stop->id) }}" method="POST" class="delete-form d-inline-block" data-type="stop" data-bs-toggle="modal" data-bs-target="#modal" data-stop="{{ $stop->title }}">
                                            @csrf
                                            @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa-solid fa-trash-alt"></i> Elimina
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="notes-section">
                                            <h6>Note:</h6>
                                            <div class="post-it">
                                                {{ $stop->notes ? $stop->notes : 'Nessuna nota disponibile' }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        @if ($stop->image)
                                        <div class="thumb-stop">
                                            <img src="{{ Vite::asset('public/storage/' . $stop->image) }}" class="img-stop" alt="{{ $stop->title }}">
                                        </div>
                                        @else
                                        <p>Nessuna immagine disponibile.</p>
                                        @endif
                                    </div>
                                
                                </div>
                            </div>
                        @endforeach

                        <!-- Bottone per aggiungere una nuova tappa -->
                        <div class="mt-3">
                            <a href="{{ route('admin.stops.create', ['trip' => $trip->slug, 'day' => $day->slug]) }}" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-plus"></i> Aggiungi Tappa
                            </a>
                        </div>
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