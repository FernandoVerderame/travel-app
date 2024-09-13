@extends('layouts.app')

@section('title', 'Viaggi')

@section('content')

    {{-- Header con il titolo della pagina e il pulsante per aggiungere un nuovo viaggio --}}
    <header class="d-flex align-items-center justify-content-between pb-4 mb-4 mt-3 border-bottom index-header">
        <h1 class="text-primary m-0">Viaggi Disponibili</h1>
        <a href="{{ route('admin.trips.create') }}" class="btn btn-success btn-lg">
            <i class="fa-solid fa-plus me-2"></i> Nuovo Viaggio
        </a>
    </header>

    {{-- Griglia di viaggi --}}
    <div class="row g-4">
        @forelse ($trips as $trip)
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100">
                    {{-- Header della card con il titolo del viaggio --}}
                    <div class="card-header bg-primary text-white">
                        <h3 class="m-0">{{ $trip->title }}</h3>
                    </div>

                    {{-- Immagine del viaggio --}}
                    @if($trip->image)
                        <img src="{{ Vite::asset('public/storage/' . $trip->image) }}" alt="{{ $trip->title }}" style="object-fit: cover; height: 200px;">
                    @endif

                    {{-- Corpo della card con la descrizione e le date --}}
                    <div class="card-body">
                        {{-- Descrizione del viaggio --}}
                        <p class="card-text text-truncate pb-2">{{ $trip->description }}</p>
                        <p class="mb-1"><strong>Partenza:</strong> {{ $trip->getFormattedDate('start_date', 'd-m-Y') }}</p>
                        <p><strong>Ritorno:</strong> {{ $trip->getFormattedDate('end_date', 'd-m-Y') }}</p>
                    </div>

                    {{-- Footer con i pulsanti di azione --}}
                    <div class="card-footer d-flex justify-content-between align-items-center bg-light">
                        <a href="{{ route('admin.trips.show', $trip->slug) }}" class="btn btn-primary">
                            <i class="fa-solid fa-rectangle-list me-1"></i> Info
                        </a>
                        <div class="d-flex">
                            <a href="{{ route('admin.trips.edit', $trip->slug) }}" class="btn btn-warning text-white me-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            {{-- Form per eliminare il viaggio --}}
                            <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" class="delete-form" data-type="trip" data-bs-toggle="modal" data-bs-target="#modal" data-trip="{{ $trip->title }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <h3 class="text-center">Non ci sono viaggi disponibili!</h3>
        @endforelse
    </div>

@endsection

@section('scripts')
    {{-- Script per la conferma di cancellazione --}}
    @vite('resources/js/delete_confirmation.js')
@endsection
