@extends('layouts.app')

@section('title', 'Viaggi')

@section('content')

    {{-- Header --}}
    <header class="d-flex align-items-center justify-content-between pb-4 mb-4 mt-3 border-bottom">
        <h1>Viaggi</h1>
        <a href="{{ route('admin.trips.create') }}" class="btn btn-success"><i class="fa-solid fa-plus me-2"></i>Nuovo</a>
    </header>

    @forelse ($trips as $trip)
    <div class="card my-5">
        <div class="card-header d-flex align-items-center justify-content-between">
        {{ $trip->title }}

        <a href="{{ route('admin.trips.show', $trip->slug) }}" class="btn btn-sm btn-primary">Info</a>
    </div>
    <div class="card-body">
        <div class="row">
            @if($trip->image)
                <div class="col-3">
                    <img src="{{ $trip->printImage() }}" class="img-fluid" alt="{{ $trip->title }}">
                </div>
            @endif

            <div class="col-9">
                <h5 class="card-title mb-2">{{ $trip->title }}</h5>
                <h6 class="card-subtitle fw-normal mb-1 text-body-secondary">
                    Partenza: {{ $trip->getFormattedDate('start_date', 'd-m-Y') }}
                </h6>
                <h6 class="card-subtitle fw-normal text-body-secondary">
                    Ritorno: {{ $trip->getFormattedDate('end_date', 'd-m-Y') }}
                </h6>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-end">
                    <a href="{{route('admin.trips.edit', $trip->slug)}}" class="btn btn-sm btn-warning text-white"><i class="fa-solid fa-pen-to-square"></i> Modifica</a>

                    <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" class="delete-form" data-bs-toggle="modal" data-bs-target="#modal" data-trip="{{ $trip->title }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger ms-2"><i class="fa-regular fa-trash-can me-1"></i>Elimina</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@empty
    <h3 class="text-center">Non ci sono viaggi!</h3>
@endforelse

@endsection

@section('scripts')
    @vite('resources/js/delete_confirmation.js')
@endsection
