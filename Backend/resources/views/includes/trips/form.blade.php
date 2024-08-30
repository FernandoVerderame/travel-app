@if($trip->exists)
    <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST" enctype="multipart/form-data" novalidate>
        @method('PUT')
@else 
    <form action="{{ route('admin.trips.store') }}" method="POST" enctype="multipart/form-data" novalidate>
@endif

    @csrf

    <div class="row">

        <div class="col-6">
            <div class="mb-4">
                <label for="title" class="form-label h4">Titolo</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @elseif(old('title', '')) is-valid @enderror" placeholder="Ex.: Arizona in camper" value="{{ old('title', $trip->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <div class="form-text">
                        Aggiungi un titolo al viaggio
                    </div>
                @enderror
            </div>
        </div>
        
        <div class="col-6">
            <div class="mb-4">
                <label for="slug" class="form-label h4">Slug</label>
                <input type="text" id="slug" class="form-control" value="{{ Str::slug(old('title', $trip->title)) }}" disabled> 
            </div>   
        </div>

        <div class="col-6">
            <div class="mb-4">
                <label for="start_date" class="form-label h4">Data di partenza</label>
                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @elseif(old('start_date', '')) is-valid @enderror" value="{{ old('start_date', $trip->start_date) }}" required>
                @error('start_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <div class="form-text">
                        Aggiungi la data di partenza
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-6">
            <div class="mb-4">
                <label for="end_date" class="form-label h4">Data di ritorno</label>
                <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @elseif(old('end_date', '')) is-valid @enderror" value="{{ old('end_date', $trip->end_date) }}" required>
                @error('end_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <div class="form-text">
                        Aggiungi la data di ritorno
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-5">
            <div class="mb-4">
                <label for="image" class="form-label h4">Immagine</label>

                <div @class(['input-group', 'd-none' => !$trip->image]) id="previous-image-field">
                    <button class="btn btn-outline-secondary" type="button" id="change-image-button">Cambia immagine</button>
                    <input type="text" class="form-control" value="{{ old('image', $trip->image) }}" disabled>
                </div>

                <input type="file" name="image" id="image" class="form-control @if($trip->image) d-none @endif @error('image') is-invalid @elseif(old('image', '')) is-valid @enderror" placeholder="Ex.: https:://...">
                
                @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <div class="form-text">
                        Carica file immagine
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-1  d-flex align-items-center">
            <div>
                <img src="{{ old('image', $trip->image) 
                ? $trip->printImage() 
                : 'https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM=' }}" class="img-fluid" alt="{{ $trip->image ? $trip->title : 'preview' }}" id="preview">
            </div>
        </div>

    </div>

    <footer class="d-flex justify-content-between align-items-center pt-4 border-top">
        <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-rotate-left me-2"></i>Indietro</a>
    
        <div>
            <button type="reset" class="btn btn-primary me-2"><i class="fa-solid fa-eraser me-2"></i>Reset</button>
            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i>Salva</button>
        </div>
    </footer>

</form>