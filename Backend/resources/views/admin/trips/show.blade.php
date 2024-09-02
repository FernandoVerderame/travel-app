@extends('layouts.app')

@section('title', $trip->title)

@section('content')

<header class="mt-3">
  <h1>{{$trip->title}}</h1>
</header>

<div class="row">
  <div class="col-12">
    <div class="">
        <h3>Itinerario</h3>
        <div id="days-container">
            @foreach($days as $day)
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="form-label">Giorno: {{ $day->number }}</h4>
                        <h5 class="fs-6">Data: {{ old('days.' . $day->id . '.date', $day->date->format('d-m-Y')) }}</h5>
                    </div>
  
                    <div class="card-body">
                        @if($day->stops->isNotEmpty())
                            <div class="mb-3">
                                <h6>Tappe:</h6>
                                <div class="row">
                                    @foreach($day->stops as $stop)
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <div class="card">
                                                @if($stop->image)
                                                    <img src="{{ Storage::url($stop->image) }}" alt="{{ $stop->title }}" class="card-img-top">
                                                @else
                                                    <img src="https://via.placeholder.com/150" alt="placeholder-image" class="card-img-top">
                                                @endif
                                                <div class="card-body">
                                                    <ul class="list-unstyled m-0">
                                                        <li>
                                                            <strong>{{ $stop->title }}</strong>
                                                        </li>
                                                        <li>
                                                            <strong>Luogo:</strong> {{ $stop->address }}
                                                        </li>
                                                        <li>
                                                            <strong>Piatti tipici:</strong> {{ $stop->foods }}
                                                        </li>
                                                        <li class="mt-2">
                                                            <div class="star-rating">
                                                                @for ($i = 5; $i >= 1; $i--)
                                                                    <i class="fa-solid fa-star fs-5 {{ $i <= $stop->rating ? 'filled' : 'empty' }}" title="{{ $i }} star"></i>
                                                                @endfor
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <div class="stop-btns d-flex justify-content-between mt-3">
                                                        <a href="{{ route('admin.stops.edit', ['trip' => $trip->slug, 'day' => $day->slug, 'stop' => $stop->slug]) }}" class="btn btn-sm btn-warning text-white"><i class="fa-solid fa-pen-to-square"></i></a>

                                                        <form action="{{ route('admin.stops.destroy', $stop->id) }}" method="POST" class="delete-form" data-type="stop" data-bs-toggle="modal" data-bs-target="#modal" data-stop="{{ $stop->title }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-regular fa-trash-can"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <p>Nessuna tappa per questo giorno.</p>
                        @endif
  
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


<div class="d-flex justify-content-between">
  <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-rotate-left me-2"></i>Indietro</a>
</div>
@endsection


@section('scripts')
    @vite('resources/js/delete_confirmation.js')
@endsection