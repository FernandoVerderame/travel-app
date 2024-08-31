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
    
  </div>

  <!-- Card footer -->
  <div class="card-footer bg-white d-flex justify-content-between">
    <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-rotate-left me-2"></i>Indietro</a>
    
    <div class="d-flex gap-2">
        <a href="{{route('admin.trips.edit', $trip->slug)}}" class="btn btn-warning text-white">Modifica</a>
        <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" class="delete-form" data-bs-toggle="modal" data-bs-target="#modal" data-trip="{{ $trip->title }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger"><i class="fa-regular fa-trash-can me-1"></i>Elimina</button>
        </form>
    </div>
  </div>
</div>
@endsection
