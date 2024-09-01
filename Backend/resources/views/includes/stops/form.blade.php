@if($stop->exists)
    <form action="{{ route('admin.stops.update', [$trip->slug, $day->slug, $stop->id]) }}" method="POST" enctype="multipart/form-data" novalidate>
        @method('PUT')
@else 
    <form action="{{ route('admin.stops.store') }}" method="POST" enctype="multipart/form-data" novalidate>
@endif

    @csrf

    <input type="hidden" name="trip_id" value="{{ $trip->id }}">
    <input type="hidden" name="day_id" value="{{ $day->id }}">

        <div class="row">

        <div class="col-6">
            <div class="mb-4">
                <label for="title" class="form-label h4">Titolo</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @elseif(old('title', '')) is-valid @enderror" placeholder="Ex.: Grand Canyon" value="{{ old('title', $stop->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <div class="form-text">
                        Aggiungi un titolo alla tappa
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-5">
            <div class="mb-4">
                <label for="image" class="form-label h4">Immagine</label>

                <div @class(['input-group', 'd-none' => !$stop->image]) id="previous-image-field">
                    <button class="btn btn-outline-secondary" type="button" id="change-image-button">Cambia immagine</button>
                    <input type="text" class="form-control" value="{{ old('image', $stop->image) }}" disabled>
                </div>

                <input type="file" name="image" id="image" class="form-control @if($stop->image) d-none @endif @error('image') is-invalid @elseif(old('image', '')) is-valid @enderror" placeholder="Ex.: https:://...">
                
                @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <div class="form-text">
                        Carica file immagine tappa
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-1  d-flex align-items-center">
            <div>
                <img src="{{ old('image', $stop->image) 
                ? $trip->printImage() 
                : 'https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM=' }}" class="img-fluid" alt="{{ $stop->image ? $stop->title : 'preview' }}" id="preview">
            </div>
        </div>

        <div class="col-12">
            <div class="mb-4">
                <label for="foods" class="form-label h4">Piatti Tipici</label>
                <textarea type="text" name="foods" id="foods" class="form-control @error('foods') is-invalid @elseif(old('foods', '')) is-valid @enderror" placeholder="Carbonara, parmigiana..." rows="3" required>{{ old('foods', $stop->foods) }}</textarea>
                @error('foods')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else 
                    <div class="form-text">
                        Aggiungi la lista dei piatti tipici
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-6">
            <div class="mb-4">
                <label for="address-search" class="form-label h4">Indirizzo</label>
                <div class="d-block card-data">
                    <div class="input-container">
                        <input id="search-address" name="address" autocomplete="off"
                                value="{{ old('address', $stop->address) }}" type="text"
                                class="form-control @error('address') is-invalid @enderror">
                        @error('address')
                                <span class="invalid-feedback error-message" role="alert">{{ $message }}</span>
                        @else
                        <div class="form-text">
                            Aggiungi la lista dei piatti tipici
                        </div>
                        @enderror
                        <ul id="suggestions" class="suggestions-list"></ul>
                    </div>
                    <span id="address-error" class="text-danger error-message"></span>
                    <input type="hidden" name="latitude" id="latitude"
                            value="{{ old('latitude', $stop->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude"
                            value="{{ old('longitude', $stop->longitude) }}">
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="mb-4">
                <label for="rating" class="form-label h4">Votazione</label>
                <div id="star-rating" class="star-rating">
                    <input type="radio" name="rating" id="rating-5" value="5"><label for="rating-5" title="5 stars"><i class="fa-solid fa-star fs-5"></i></label>
                    <input type="radio" name="rating" id="rating-4" value="4"><label for="rating-4" title="4 stars"><i class="fa-solid fa-star fs-5"></i></label>
                    <input type="radio" name="rating" id="rating-3" value="3"><label for="rating-3" title="3 stars"><i class="fa-solid fa-star fs-5"></i></label>
                    <input type="radio" name="rating" id="rating-2" value="2"><label for="rating-2" title="2 stars"><i class="fa-solid fa-star fs-5"></i></label>
                    <input type="radio" name="rating" id="rating-1" value="1"><label for="rating-1" title="1 star"><i class="fa-solid fa-star fs-5"></i></label>
                </div>
                @error('rating')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @else 
                <div class="form-text">
                    Seleziona un voto da 1 a 5 stelle
                </div>
                @enderror
            </div>
        </div>

        </div>

        <footer class="d-flex justify-content-between align-items-center pt-4 border-top">
            <a href="{{ route('admin.trips.show', $trip->slug) }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-rotate-left me-2"></i>Indietro</a>



    
            <div>
                <button type="reset" class="btn btn-primary me-2"><i class="fa-solid fa-eraser me-2"></i>Reset</button>
                <button type="submit" class="btn btn-success" id="save-btn"><i class="fa-solid fa-floppy-disk me-2"></i>Salva</button>
            </div>
        </footer>

    </form>