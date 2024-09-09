<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Day;
use App\Models\Stop;
use App\Models\Trip;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($trip, $day)
    {
        // Recupera il viaggio e il giorno tramite gli slug
        $trip = Trip::where('slug', $trip)->firstOrFail();
        $day = Day::where('slug', $day)->firstOrFail();

        // Recupera tutte le categorie
        $categories = Category::all();

        $stop = new Stop(); // Crea una nuova istanza di Stop

        // Passa i dati alla vista di creazione
        return view('admin.stops.create', compact('stop', 'trip', 'day', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $trip, $day)
    {
        // Recupera il viaggio e il giorno tramite gli slug
        $trip = Trip::where('slug', $trip)->firstOrFail();
        $day = Day::where('slug', $day)->where('trip_id', $trip->id)->firstOrFail();

        // Validazione dei dati della tappa
        $request->validate([
            'title' => 'required|string|min:5|max:50|unique:stops',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'foods' => 'nullable|string',
            'notes' => 'nullable|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'expected_duration' => 'nullable|date_format:H:i',
            'day_id' => 'required|exists:days,id',
            'category_id' => 'nullable|exists:categories,id'
        ], [
            // Messaggi di errore personalizzati per la validazione
            'title.required' => 'Il titolo è obbligatorio',
            'title.min' => 'Il titolo deve essere di almeno :min caratteri',
            'title.max' => 'Il titolo deve essere di un massimo di :max caratteri',
            'title.unique' => 'Non possono esserci due tappe con lo stesso titolo',
            'image.image' => 'Il file aggiunto non è un\'immagine',
            'image.mimes' => 'Le estensioni valide sono .png, .jpg, .jpeg',
            'foods.string' => 'I piatti tipici devono essere una stringa di testo',
            'notes.string' => 'Le note devono essere una stringa di testo',
            'notes.max' => 'Le note possono contenere un massimo di 255 caratteri',
            'address.required' => 'L\'indirizzo è obbligatorio',
            'address.string' => 'L\'indirizzo deve essere una stringa di testo',
            'latitude.required' => 'La latitudine è obbligatoria',
            'latitude.numeric' => 'La latitudine deve essere un numero valido',
            'longitude.required' => 'La longitudine è obbligatoria',
            'longitude.numeric' => 'La longitudine deve essere un numero valido',
            'expected_duration.date_format' => 'L\'orario deve essere nel formato HH:MM',
            'day_id.required' => 'Il giorno è obbligatorio',
            'day_id.exists' => 'Il giorno selezionato non esiste',
            'category_id.exists' => 'Categoria non valida o non esistente'
        ]);

        $data = $request->all(); // Ottiene tutti i dati dalla richiesta

        $stop = new Stop(); // Crea una nuova istanza di Stop

        $stop->fill($data); // Riempie l'istanza con i dati della richiesta
        $stop->day_id = $day->id; // Assegna l'ID del giorno

        $stop->slug = Str::slug($stop->title); // Genera uno slug per la tappa

        // Controlla se è stato caricato un file immagine
        if (Arr::exists($data, 'image')) {
            $extension = $data['image']->extension(); // Ottiene l'estensione dell'immagine

            // Salva l'immagine e ottiene l'URL
            $img_url = Storage::putFileAs('stop_images', $data['image'], "$stop->slug.$extension");
            $stop->image = $img_url;
        }

        $stop->save(); // Salva la tappa nel database

        // Reindirizza alla vista del viaggio con un messaggio di successo
        return to_route('admin.trips.show', $trip->slug)->with('type', 'success')->with('message', 'Nuovo tappa creata con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stop $stop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($trip, $day, $stop)
    {
        // Recupera il viaggio, il giorno e la tappa tramite gli slug
        $trip = Trip::where('slug', $trip)->firstOrFail();
        $day = Day::where('slug', $day)->firstOrFail();
        $stop = Stop::where('slug', $stop)->firstOrFail();

        // Recupera tutte le categorie
        $categories = Category::all();

        // Passa i dati alla vista di modifica
        return view('admin.stops.edit', compact('trip', 'day', 'stop', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $trip, $day, Stop $stop)
    {
        // Recupera il viaggio e il giorno tramite gli slug
        $trip = Trip::where('slug', $trip)->firstOrFail();
        $day = Day::where('slug', $day)->where('trip_id', $trip->id)->firstOrFail();

        // Validazione dei dati della tappa
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:50', Rule::unique('stops')->ignore($stop->id)],
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'foods' => 'nullable|string',
            'notes' => 'nullable|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'expected_duration' => 'nullable|date_format:H:i',
            'day_id' => 'required|exists:days,id',
            'category_id' => 'nullable|exists:categories,id'
        ], [
            // Messaggi di errore personalizzati per la validazione
            'title.required' => 'Il titolo è obbligatorio',
            'title.min' => 'Il titolo deve essere di almeno :min caratteri',
            'title.max' => 'Il titolo deve essere di un massimo di :max caratteri',
            'title.unique' => 'Non possono esserci due tappe con lo stesso titolo',
            'image.image' => 'Il file aggiunto non è un\'immagine',
            'image.mimes' => 'Le estensioni valide sono .png, .jpg, .jpeg',
            'foods.string' => 'I piatti tipici devono essere una stringa di testo',
            'notes.string' => 'Le note devono essere una stringa di testo',
            'notes.max' => 'Le note possono contenere un massimo di 255 caratteri',
            'address.required' => 'L\'indirizzo è obbligatorio',
            'address.string' => 'L\'indirizzo deve essere una stringa di testo',
            'latitude.required' => 'La latitudine è obbligatoria',
            'latitude.numeric' => 'La latitudine deve essere un numero valido',
            'longitude.required' => 'La longitudine è obbligatoria',
            'longitude.numeric' => 'La longitudine deve essere un numero valido',
            'expected_duration.date_format' => 'L\'orario deve essere nel formato HH:MM',
            'day_id.required' => 'Il giorno è obbligatorio',
            'day_id.exists' => 'Il giorno selezionato non esiste',
            'category_id.exists' => 'Categoria selezionata non valida'
        ]);

        $data = $request->all(); // Ottiene tutti i dati dalla richiesta

        // Mantiene l'ID del giorno se non modificato
        if (!isset($data['day_id'])) {
            $data['day_id'] = $stop->day_id;
        }

        $stop->fill($data); // Riempie l'istanza con i dati della richiesta
        $stop->slug = Str::slug($stop->title); // Aggiorna lo slug
        $stop->day_id = $day->id; // Aggiorna l'ID del giorno

        // Controlla se è stato caricato un file immagine
        if ($request->hasFile('image')) {
            // Elimina l'immagine esistente se presente
            if ($stop->image) {
                Storage::delete($stop->image);
            }

            $extension = $data['image']->extension(); // Ottiene l'estensione dell'immagine
            $img_url = Storage::putFileAs('stop_images', $data['image'], "$stop->slug.$extension"); // Salva la nuova immagine
            $stop->image = $img_url;
        }

        $stop->save(); // Salva le modifiche nel database

        // Reindirizza alla vista del viaggio con un messaggio di successo
        return to_route('admin.trips.show', $trip->slug)->with('type', 'success')->with('message', 'Tappa aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stop $stop)
    {
        // Recupera il viaggio associato tramite il giorno
        $trip = $stop->day->trip;

        // Elimina l'immagine della tappa se esiste
        if ($stop->image) {
            Storage::delete($stop->image);
        }

        $stop->delete(); // Elimina la tappa dal database

        // Reindirizza alla vista del viaggio con un messaggio di successo
        return to_route('admin.trips.show', $trip->slug)->with('type', 'danger')->with('message', "Tappa {$stop->title} eliminata con successo!");
    }
}
