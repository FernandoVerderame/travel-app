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
   
  </div>
</div>
@endsection
