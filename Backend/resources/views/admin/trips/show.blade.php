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
            {{-- Ciclo attraverso i giorni del viaggio --}}
            @foreach($days as $day)
                <div class="card mb-4">
                    {{-- Mostra il numero e la data del giorno --}}
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h4 class="form-label">Giorno: {{ $day->number }}</h4>
                            <h5 class="fs-6">Data: {{ old('days.' . $day->id . '.date', $day->date->format('d-m-Y')) }}</h5>
                        </div>

                        <!-- Sezione previsioni meteo -->
                        @if($day->weather)
                            <div class="weather-info">
                                <h6>Previsioni meteo:</h6>
                                {{-- Mostra la temperatura e una descrizione meteo --}}
                                <p class="m-0">Temperatura: <strong>{{ $day->weather['temperature'] }}°C</strong></p>
                                <p class="m-0">Descrizione: <strong>{{ ucfirst($day->weather['description']) }}</strong></p>
                                {{-- Icona meteo
                                <img src="http://www.weatherbit.io/static/img/icons/{{ $day->weather['icon'] }}.png" alt="Icona Meteo"> --}}
                            </div>
                        @else
                            {{-- Messaggio se non ci sono informazioni meteo --}}
                            <p>Nessuna informazione meteo disponibile.</p>
                        @endif
                    </div>
  
                    <div class="card-body">
                        {{-- Se ci sono tappe nel giorno, le mostriamo --}}
                        @if($day->stops->isNotEmpty())
                            <div class="mb-3">
                                <h6>Tappe:</h6>
                                <div class="row">
                                    {{-- Ciclo attraverso le tappe del giorno --}}
                                    @foreach($day->stops as $stop)
                                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                                            <div class="card">
                                                {{-- Verifica se c'è un'immagine per la tappa --}}
                                                @if($stop->image)
                                                <figure class="thumb m-0">
                                                    {{-- Mostra l'immagine della tappa --}}
                                                    <img src="{{ Vite::asset('public/storage/' . $stop->image) }}" alt="{{ $stop->title }}" class="card-img-top">
                                                    {{-- Mostra un'icona per la categoria della tappa, se disponibile --}}
                                                    @if ($stop->category !== null)
                                                        <span class="badge text-bg-danger">
                                                            <i class="fa solid {{ $stop->category->icon }}"></i>
                                                        </span>
                                                    @endif
                                                </figure>
                                                @else
                                                {{-- Placeholder immagine se la tappa non ha un'immagine --}}
                                                <figure class="thumb m-0">
                                                    <img src="https://via.placeholder.com/150" alt="placeholder-image" class="card-img-top">
                                                    @if ($stop->category !== null)
                                                    <span class="badge text-bg-danger">
                                                        <i class="fa solid {{ $stop->category->icon }}"></i>
                                                    </span>
                                                    @endif
                                                </figure>
                                                @endif
                                                <div class="card-body">
                                                    {{-- Mostra l'orario della tappa o "Ora da stabilire" se non definita --}}
                                                    @if ($stop->expected_duration)
                                                        <div class="d-flex justify-content-center">
                                                            <span class="badge rounded-pill text-bg-info">{{ $stop->expected_duration }}</span>
                                                        </div>
                                                    @else
                                                        <p class="m-0 text-center">Ora da stabilire</p>
                                                    @endif
                                                    {{-- Dettagli della tappa --}}
                                                    <ul class="list-unstyled m-0">
                                                        <li><strong>{{ $stop->title }}</strong></li>
                                                        <li><strong>Luogo:</strong> {{ $stop->address }}</li>
                                                        <li><strong>Piatti tipici:</strong> {{ $stop->foods }}</li>
                                                        {{-- Valutazione della tappa con stelle --}}
                                                        <li class="mt-2">
                                                            <div class="star-rating">
                                                                @for ($i = 5; $i >= 1; $i--)
                                                                    <i class="fa-solid fa-star fs-5 {{ $i <= $stop->rating ? 'filled' : 'empty' }}" title="{{ $i }} star"></i>
                                                                @endfor
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    {{-- Pulsanti per la gestione della tappa --}}
                                                    <div class="stop-btns d-flex justify-content-between align-items-center mt-3">
                                                        {{-- Modifica tappa --}}
                                                        <a href="{{ route('admin.stops.edit', ['trip' => $trip->slug, 'day' => $day->slug, 'stop' => $stop->slug]) }}" class="btn btn-sm btn-warning text-white">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>

                                                        {{-- Tooltip per mostrare le note della tappa --}}
                                                        <button type="button" class="border-0 btn-tool bg-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="{{ $stop->notes ?? 'Nessuna nota disponibile' }}">
                                                            <i class="fa-solid fa-info text-white"></i>
                                                        </button>

                                                        {{-- Form per eliminare la tappa --}}
                                                        <form action="{{ route('admin.stops.destroy', $stop->id) }}" method="POST" class="delete-form" data-type="stop" data-bs-toggle="modal" data-bs-target="#modal" data-stop="{{ $stop->title }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            {{-- Messaggio se non ci sono tappe per il giorno --}}
                            <p>Nessuna tappa per questo giorno.</p>
                        @endif
  
                        {{-- Pulsante per aggiungere una nuova tappa al giorno --}}
                        <a href="{{ route('admin.stops.create', ['trip' => $trip->slug, 'day' => $day->slug]) }}" class="btn btn-sm btn-success text-white">
                            <i class="fa-solid fa-plus me-1"></i>Tappa
                        </a>
                    </div>
                </div>
            @endforeach
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