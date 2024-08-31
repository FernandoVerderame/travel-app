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

            <div class="col">
                <h5 class="card-title mb-2">{{ $trip->title }}</h5>
                <h6 class="card-subtitle fw-normal mb-1 text-body-secondary">
                    Partenza: {{ $trip->getFormattedDate('start_date', 'd-m-Y') }}
                </h6>
                <h6 class="card-subtitle fw-normal text-body-secondary">
                    Ritorno: {{ $trip->getFormattedDate('end_date', 'd-m-Y') }}
                </h6>
            </div>
        </div>
    </div>
</div>
@empty
    <h3 class="text-center">Non ci sono viaggi!</h3>
@endforelse

@endsection

@section('scripts')

@endsection
