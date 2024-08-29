@extends('layouts.app')

@section('title', 'Home')

@section('content')

<header class="pb-3 border-bottom">
    <h1 class="mt-3">Boolfolio</h1>
    <h3 class="mb-3">Discover my projects</h3>

    @if($projects->hasPages())
        {{ $projects->links() }}
    @endif
</header>

@forelse ($projects as $project)
<div class="card my-5">
    <div class="card-header d-flex align-items-center justify-content-between">
        {{ $project->title }}

        <a href="{{ route('guest.projects.show', $project->slug) }}" class="btn btn-sm btn-primary">Show</a>
    </div>
    <div class="card-body">
        <div class="row">
            @if($project->image)
                <div class="col-3">
                    <img src="{{ $project->printImage() }}" class="img-fluid" alt="{{ $project->title }}">
                </div>
            @endif

            <div class="col">
                <h5 class="card-title mb-3">{{ $project->title }}</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">{{ $project->created_at }}</h6>
                <p class="card-text">{{ $project->description }}</p>
            </div>
        </div>
    </div>
</div>
@empty
    <h3 class="text-center">There aren't any projects!</h3>
@endforelse

@endsection