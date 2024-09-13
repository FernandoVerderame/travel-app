<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use GuzzleHttp\Client;

class TripController extends Controller
{
    /**
     * Display a listing of the trips for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user(); // Ottiene l'utente autenticato

        // Recupera tutti i viaggi dell'utente ordinati per data di aggiornamento e creazione
        $trips = Trip::whereUserId($user->id)->orderByDesc('updated_at')->orderByDesc('created_at')->get();

        return view('admin.trips.index', compact('trips')); // Passa i viaggi alla vista
    }

    /**
     * Show the form for creating a new trip.
     */
    public function create()
    {
        $trip = new Trip(); // Crea un nuovo oggetto Trip

        return view('admin.trips.create', compact('trip')); // Passa l'oggetto Trip alla vista di creazione
    }

    /**
     * Store a newly created trip in storage.
     */
    public function store(Request $request)
    {
        // Validazione dei dati del viaggio
        $request->validate([
            'title' => 'required|string|min:5|max:50|unique:trips',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'start_date' => 'required|date|after_or_equal:today|unique:trips',
            'end_date' => 'required|date|after_or_equal:start_date|unique:trips'
        ], [
            // Messaggi di errore personalizzati per la validazione
            'title.required' => 'Il titolo è obbligatorio',
            'title.min' => 'Il titolo deve essere di almeno :min caratteri',
            'title.max' => 'Il titolo deve essere di un massimo di :max caratteri',
            'title.unique' => 'Non possono esserci due viaggi con lo stesso titolo',
            'image.image' => 'Il file aggiunto non è un\'immagine',
            'image.mimes' => 'Le estensioni valide sono .png, .jpg, .jpeg',
            'start_date.required' => 'La data di inizio è obbligatoria',
            'start_date.date' => 'La data di inizio non è una data valida',
            'start_date.after_or_equal' => 'La data di inizio deve essere oggi o una data futura',
            'start_date.unique' => 'Esiste già un viaggio con questa data di inizio',
            'end_date.required' => 'La data di fine è obbligatoria',
            'end_date.date' => 'La data di fine non è una data valida',
            'end_date.after_or_equal' => 'La data di fine deve essere uguale o successiva alla data di inizio',
            'end_date.unique' => 'Esiste già un viaggio con questa data di fine'
        ]);

        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); // Genera un colore casuale per il viaggio

        $data = $request->all(); // Ottiene tutti i dati dalla richiesta
        $data['color'] = $color; // Aggiunge il colore ai dati

        $trip = new Trip(); // Crea una nuova istanza di Trip
        $trip->fill($data); // Riempie l'istanza con i dati della richiesta
        $trip->slug = Str::slug($trip->title); // Genera uno slug per il viaggio

        // Controlla se è stato caricato un file immagine
        if (Arr::exists($data, 'image')) {
            $extension = $data['image']->extension(); // Ottiene l'estensione dell'immagine

            // Salva l'immagine e ottiene l'URL
            $img_url = Storage::putFileAs('trip_images', $data['image'], "$trip->slug.$extension");
            $trip->image = $img_url;
        }

        $trip->user_id = Auth::id(); // Assegna l'ID dell'utente autenticato al viaggio

        $trip->save(); // Salva il viaggio nel database

        $trip->generateDays($request->start_date, $request->end_date); // Genera i giorni per il viaggio

        return to_route('admin.trips.show', $trip->slug)->with('type', 'success')->with('message', 'Nuovo viaggio creato con successo!');
    }

    /**
     * Display the specified trip.
     */
    public function show(string $slug)
    {
        $trip = Trip::whereSlug($slug)->with('days.stops')->first(); // Recupera il viaggio specificato dallo slug

        if (!$trip) abort(404); // Mostra errore 404 se il viaggio non esiste

        // Ottieni i giorni del viaggio e le relative previsioni meteo
        $days = $trip->days->map(function ($day) {
            $day->date = Carbon::parse($day->date); // Converti la data in un'istanza Carbon
            $day->slug = Str::slug('Giorno ' . $day->number); // Genera uno slug per il giorno

            // Recupera la prima tappa del giorno e le previsioni meteo
            $firstStop = $day->stops->first();
            if ($firstStop) {
                $day->weather = $this->getWeather($firstStop->latitude, $firstStop->longitude);
            } else {
                $day->weather = null; // Nessuna previsione meteo se non ci sono tappe
            }

            return $day;
        });

        return view('admin.trips.show', compact('trip', 'days')); // Passa il viaggio e i giorni alla vista
    }

    /**
     * Show the form for editing the specified trip.
     */
    public function edit(string $slug)
    {
        $trip = Trip::whereSlug($slug)->first(); // Recupera il viaggio specificato dallo slug

        if (!$trip) abort(404); // Mostra errore 404 se il viaggio non esiste

        return view('admin.trips.edit', compact('trip')); // Passa il viaggio alla vista di modifica
    }

    /**
     * Update the specified trip in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        // Validazione dei dati del viaggio
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:50', Rule::unique('trips')->ignore($trip->id)],
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'start_date' => ['required', 'date', 'after_or_equal:today', Rule::unique('trips')->ignore($trip->id)],
            'end_date' => ['required', 'date', 'after_or_equal:start_date', Rule::unique('trips')->ignore($trip->id)]
        ], [
            // Messaggi di errore personalizzati per la validazione
            'title.required' => 'Il titolo è obbligatorio',
            'title.min' => 'Il titolo deve essere di almeno :min caratteri',
            'title.max' => 'Il titolo deve essere di un massimo di :max caratteri',
            'title.unique' => 'Non possono esserci due viaggi con lo stesso titolo',
            'image.image' => 'Il file aggiunto non è un\'immagine',
            'image.mimes' => 'Le estensioni valide sono .png, .jpg, .jpeg',
            'start_date.required' => 'La data di inizio è obbligatoria',
            'start_date.date' => 'La data di inizio non è una data valida',
            'start_date.after_or_equal' => 'La data di inizio deve essere oggi o una data futura',
            'start_date.unique' => 'Esiste già un viaggio con questa data di inizio',
            'end_date.required' => 'La data di fine è obbligatoria',
            'end_date.date' => 'La data di fine non è una data valida',
            'end_date.after_or_equal' => 'La data di fine deve essere uguale o successiva alla data di inizio',
            'end_date.unique' => 'Esiste già un viaggio con questa data di fine'
        ]);

        $data = $request->all(); // Ottiene tutti i dati dalla richiesta
        $data['slug'] = Str::slug($data['title']); // Aggiorna lo slug

        // Mantiene il colore se già presente, altrimenti usa il colore esistente
        if (isset($data['color']) && !empty($data['color'])) {
            $trip->color = $data['color'];
        } else {
            $data['color'] = $trip->color;
        }

        // Controlla se è stato caricato un file immagine
        if (Arr::exists($data, 'image')) {
            // Elimina l'immagine precedente se esiste
            if ($trip->image) Storage::delete($trip->image);

            $extension = $data['image']->extension(); // Ottiene l'estensione dell'immagine

            // Salva la nuova immagine e ottiene l'URL
            $img_url = Storage::putFileAs('trip_images', $data['image'], "{$data['slug']}.$extension");
            $trip->image = $img_url;
        }

        $trip->update($data); // Aggiorna il viaggio nel database

        $trip->generateDays($request->start_date, $request->end_date, true); // Rigenera i giorni per il viaggio

        return to_route('admin.trips.show', $trip->slug)->with('type', 'success')->with('message', 'Viaggio aggiornato con successo!');
    }

    /**
     * Remove the specified trip from storage.
     */
    public function destroy(Trip $trip)
    {
        // Recupera tutte le tappe (stops) attraverso i giorni associati al viaggio
        $days = $trip->days;
        foreach ($days as $day) {
            foreach ($day->stops as $stop) {
                // Elimina l'immagine della tappa se esiste
                if ($stop->image) {
                    Storage::delete($stop->image);
                }
                // Elimina la tappa
                $stop->delete();
            }
        }

        // Elimina l'immagine del viaggio se esiste
        if ($trip->image) Storage::delete($trip->image);

        $trip->delete(); // Elimina il viaggio dal database

        return to_route('admin.trips.index')->with('type', 'danger')->with('message', "{$trip->title} eliminato con successo!");
    }

    /**
     * Retrieve the weather forecast based on latitude and longitude.
     */
    private function getWeather($latitude, $longitude)
    {
        try {
            $client = new Client(); // Crea una nuova istanza del client HTTP
            $apiKey = env('WEATHERBIT_API_KEY'); // Ottiene la chiave API dalle variabili d'ambiente
            $response = $client->get("http://api.weatherbit.io/v2.0/current?lat={$latitude}&lon={$longitude}&key={$apiKey}&lang=it");
            $data = json_decode($response->getBody(), true); // Decodifica la risposta JSON

            return [
                'temperature' => $data['data'][0]['temp'],
                'description' => $data['data'][0]['weather']['description'],
                'icon' => $data['data'][0]['weather']['icon']
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() == 429) {
                // Gestisce l'errore di "Too Many Requests"
                return [
                    'temperature' => 'N/A',
                    'description' => 'Info non disponibili',
                    'icon' => 'default_icon.png'
                ];
            }
            throw $e; // Rilancia l'eccezione se non è un 429
        }
    }
}
