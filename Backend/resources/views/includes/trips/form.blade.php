@if($trip->exists)
    <!-- Se il viaggio esiste, usa il form per l'aggiornamento (PUT) -->
    <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST" enctype="multipart/form-data" novalidate>
        @method('PUT') <!-- Metodo HTTP per aggiornare una risorsa esistente -->
@else 
    <!-- Se il viaggio non esiste, usa il form per creare un nuovo viaggio (POST) -->
    <form action="{{ route('admin.trips.store') }}" method="POST" enctype="multipart/form-data" novalidate>
@endif

    @csrf <!-- Token di protezione CSRF richiesto da Laravel -->

    <div class="row">

        <div class="col-6">
            <div class="mb-4">
                <!-- Campo per il titolo del viaggio -->
                <label for="title" class="form-label h4">Titolo</label>
                <input type="text" name="title" id="title" 
                    class="form-control @error('title') is-invalid @elseif(old('title', '')) is-valid @enderror" 
                    placeholder="Ex.: Arizona in camper" 
                    value="{{ old('title', $trip->title) }}" required>
                
                @error('title')
                    <!-- Mostra il messaggio di errore se la validazione del titolo fallisce -->
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <!-- Testo guida per il campo titolo -->
                    <div class="form-text">
                        Aggiungi un titolo al viaggio
                    </div>
                @enderror
            </div>
        </div>
        
        <div class="col-6">
            <div class="mb-4">
                <!-- Campo per lo slug, disabilitato perché generato automaticamente -->
                <label for="slug" class="form-label h4">Slug</label>
                <input type="text" id="slug" class="form-control" 
                    value="{{ Str::slug(old('title', $trip->title)) }}" disabled> 
            </div>   
        </div>

        <div class="col-6">
            <div class="mb-4">
                <!-- Campo per la data di partenza -->
                <label for="start_date" class="form-label h4">Data di partenza</label>
                <input type="date" name="start_date" id="start_date" 
                    class="form-control @error('start_date') is-invalid @enderror"
                    value="{{ old('start_date', $trip->start_date ? $trip->start_date->format('Y-m-d') : null) }}" required>
                
                @error('start_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <!-- Testo guida per il campo data di partenza -->
                    <div class="form-text">
                        Aggiungi la data di partenza
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-6">
            <div class="mb-4">
                <!-- Campo per la data di ritorno -->
                <label for="end_date" class="form-label h4">Data di ritorno</label>
                <input type="date" name="end_date" id="end_date" 
                    class="form-control @error('end_date') is-invalid @enderror"
                    value="{{ old('end_date', $trip->end_date ? $trip->end_date->format('Y-m-d') : null) }}" required>
                
                @error('end_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <!-- Testo guida per il campo data di ritorno -->
                    <div class="form-text">
                        Aggiungi la data di ritorno
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-12">
            <div class="mb-4">
                <!-- Campo per la descrizione del viaggio -->
                <label for="description" class="form-label h4">Descrizione</label>
                <textarea name="description" id="description" 
                    class="form-control @error('description') is-invalid @elseif(old('description', '')) is-valid @enderror" 
                    placeholder="Descrizione del viaggio..." rows="8" required>{{ old('description', $trip->description) }}</textarea>
                
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <!-- Testo guida per il campo descrizione -->
                    <div class="form-text">
                        Aggiungi una descrizione del progetto
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-5">
            <div class="mb-4">
                <!-- Campo per il caricamento dell'immagine -->
                <label for="image" class="form-label h4">Immagine</label>

                <!-- Gruppo di input per l'immagine precedente -->
                <div @class(['input-group', 'd-none' => !$trip->image]) id="previous-image-field">
                    <button class="btn btn-outline-secondary" type="button" id="change-image-button">Cambia immagine</button>
                    <input type="text" class="form-control" value="{{ old('image', $trip->image) }}" disabled>
                </div>

                <!-- Input per caricare una nuova immagine -->
                <input type="file" name="image" id="image" 
                    class="form-control @if($trip->image) d-none @endif @error('image') is-invalid @elseif(old('image', '')) is-valid @enderror" placeholder="Ex.: https:://...">
                
                @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <!-- Testo guida per il caricamento dell'immagine -->
                    <div class="form-text">
                        Carica file immagine
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-1 d-flex align-items-center">
            <div>
                <!-- Campo di anteprima per l'immagine -->
                <img src="{{ old('image', $trip->image) 
                ? $trip->printImage() 
                : 'https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM=' }}" 
                class="img-fluid" alt="{{ $trip->image ? $trip->title : 'preview' }}" id="preview">
            </div>
        </div>

    </div>

    <!-- Footer del form con i pulsanti di azione -->
    <footer class="d-flex justify-content-between align-items-center pt-4 border-top">
        <!-- Pulsante per tornare indietro -->
        <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-rotate-left me-2"></i>Indietro
        </a>
    
        <div>
            <!-- Pulsante di reset -->
            <button type="reset" class="btn btn-primary me-2">
                <i class="fa-solid fa-eraser me-2"></i>Reset
            </button>
            <!-- Pulsante di salvataggio -->
            <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-floppy-disk me-2"></i>Salva
            </button>
        </div>
    </footer>

</form>