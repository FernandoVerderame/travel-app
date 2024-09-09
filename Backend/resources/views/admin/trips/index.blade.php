@extends('layouts.app')

@section('title', 'Viaggi')

@section('content')

    {{-- Header con il titolo della pagina e il pulsante per aggiungere un nuovo viaggio --}}
    <header class="d-flex align-items-center justify-content-between pb-4 mb-4 mt-3 border-bottom">
        <h1>Viaggi</h1>
        <a href="{{ route('admin.trips.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-2"></i>Nuovo
        </a>
    </header>

    {{-- Ciclo attraverso tutti i viaggi --}}
    @forelse ($trips as $trip)
    <div class="card my-5">
        {{-- Header della card con il titolo del viaggio e il pulsante per vedere i dettagli --}}
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="m-0">{{ $trip->title }}</h3>
            <a href="{{ route('admin.trips.show', $trip->slug) }}" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-rectangle-list me-1"></i> Info
            </a>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Se esiste un'immagine associata al viaggio, viene visualizzata --}}
                @if($trip->image)
                    <div class="col-3">
                        <figure class="thumb">
                            <img src="{{ Vite::asset('public/storage/' . $trip->image) }}" alt="{{ $trip->title }}">
                        </figure>
                    </div>
                @endif

                {{-- Descrizione del viaggio, mostrata in un elemento <details> --}}
                <div class="col-9">
                    <details>{{ $trip->description }}</details>
                </div>

                {{-- Date di partenza e ritorno formattate --}}
                <div class="col-3">
                    <h6 class="card-subtitle fw-normal mb-1 text-body-secondary">
                        Partenza: {{ $trip->getFormattedDate('start_date', 'd-m-Y') }}
                    </h6>
                    <h6 class="card-subtitle fw-normal text-body-secondary mb-3">
                        Ritorno: {{ $trip->getFormattedDate('end_date', 'd-m-Y') }}
                    </h6>
                </div>

                {{-- Pulsanti di azione per modificare o eliminare il viaggio --}}
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        {{-- Pulsante per modificare il viaggio --}}
                        <a href="{{route('admin.trips.edit', $trip->slug)}}" class="btn btn-sm btn-warning text-white">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Modifica
                        </a>

                        {{-- Form per eliminare il viaggio con la conferma tramite un modal --}}
                        <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" class="delete-form" data-type="trip" data-bs-toggle="modal" data-bs-target="#modal" data-trip="{{ $trip->title }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger ms-2">
                                <i class="fa-regular fa-trash-can me-1"></i>Elimina
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
        {{-- Messaggio che appare se non ci sono viaggi --}}
        <h3 class="text-center">Non ci sono viaggi!</h3>
    @endforelse

@endsection

@section('scripts')
    {{-- Importa lo script per la conferma della cancellazione --}}
    @vite('resources/js/delete_confirmation.js')
@endsection
