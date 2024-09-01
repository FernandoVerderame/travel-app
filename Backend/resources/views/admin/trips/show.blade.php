@extends('layouts.app')

@section('title', $trip->title)

@section('content')
<div class="card mt-5">

  <!-- Card header -->
  <div class="card-header bg-white">
    <h4 class="card-title">{{$trip->title}}</h4>
  </div>

  <!-- Card body -->
  <div class="card-body">
    <div class="col-12">
        <div class="mb-4">
            <h3>Itinerario</h3>
            <div id="days-container">
                @foreach($days as $day)
                    <div class="card p-2 day-entry mb-3">
                        <h4 for="day_date_{{ $day->id }}" class="form-label fs-6">Giorno {{ $day->number }}: Titolo</h4>
                        <h5 class="fs-6">Data: {{ old('days.' . $day->id . '.date', $day->date->format('d-m-Y')) }}</h5>
                        <a href="{{ route('admin.stops.create', ['trip' => $trip->id, 'day' => $day->id]) }}" class="btn btn-sm btn-success text-white"><i class="fa-solid fa-plus me-1"></i>Tappa</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
  </div>

  <!-- Card footer -->
  <div class="card-footer bg-white d-flex justify-content-between">
    <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-rotate-left me-2"></i>Indietro</a>
    
    <div class="d-flex gap-2">
        {{-- <a href="{{route('admin.trips.edit', $trip->slug)}}" class="btn btn-warning text-white">Modifica</a> --}}
        {{-- <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" class="delete-form" data-bs-toggle="modal" data-bs-target="#modal" data-trip="{{ $trip->title }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger"><i class="fa-regular fa-trash-can me-1"></i>Elimina</button>
        </form> --}}
    </div>
  </div>
</div>
@endsection
