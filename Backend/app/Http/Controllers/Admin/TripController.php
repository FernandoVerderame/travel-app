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

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $trips = Trip::whereUserId($user->id)->orderByDesc('updated_at')->orderByDesc('created_at')->get();

        return view('admin.trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trip = new Trip();

        return view('admin.trips.create', compact('trip'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:5|max:50|unique:trips',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'start_date' => 'required|date|after_or_equal:today|unique:trips',
            'end_date' => 'required|date|after_or_equal:start_date|unique:trips'
        ], [
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

        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

        $data = $request->all();

        $data['color'] = $color;

        $trip = new Trip();

        $trip->fill($data);
        $trip->slug = Str::slug($trip->title);

        // New file check
        if (Arr::exists($data, 'image')) {
            $extension = $data['image']->extension();

            // Save URL
            $img_url = Storage::putFileAs('trip_images', $data['image'], "$trip->slug.$extension");
            $trip->image = $img_url;
        }

        $trip->user_id = Auth::id();

        $trip->save();

        $trip->generateDays($request->start_date, $request->end_date);

        return to_route('admin.trips.show', $trip->slug)->with('type', 'success')->with('message', 'Nuovo viaggio creato con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $trip = Trip::whereSlug($slug)->with('days.stops')->first();

        if (!$trip) abort(404);

        $days = $trip->days->map(function ($day) {
            $day->date = Carbon::parse($day->date);
            $day->slug = Str::slug('Giorno ' . $day->number);
            return $day;
        });

        return view('admin.trips.show', compact('trip', 'days'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $trip = Trip::whereSlug($slug)->first();

        if (!$trip) abort(404);

        return view('admin.trips.edit', compact('trip'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:50', Rule::unique('trips')->ignore($trip->id)],
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'start_date' => ['required', 'date', 'after_or_equal:today', Rule::unique('trips')->ignore($trip->id)],
            'end_date' => ['required', 'date', 'after_or_equal:start_date', Rule::unique('trips')->ignore($trip->id)]
        ], [
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

        $data = $request->all();

        $data['slug'] = Str::slug($data['title']);

        if (isset($data['color']) && !empty($data['color'])) {
            $trip->color = $data['color'];
        } else {
            $data['color'] = $trip->color;
        }

        // New file check
        if (Arr::exists($data, 'image')) {
            // Check if there is an old image
            if ($trip->image) Storage::delete($trip->image);

            $extension = $data['image']->extension();

            // Save URL
            $img_url = Storage::putFileAs('trip_images', $data['image'], "{$data['slug']}.$extension");
            $trip->image = $img_url;
        }

        $trip->update($data);

        $trip->generateDays($request->start_date, $request->end_date, true);

        return to_route('admin.trips.show', $trip->slug)->with('type', 'success')->with('message', 'Viaggio aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        // Recupera tutte le tappe (stops) attraverso i giorni associati al viaggio
        $days = $trip->days;
        foreach ($days as $day) {
            foreach ($day->stops as $stop) {
                // Elimina l'immagine della stop se esiste
                if ($stop->image) {
                    Storage::delete($stop->image);
                }
                // Elimina la stop
                $stop->delete();
            }
        }

        if ($trip->image) Storage::delete($trip->image);

        $trip->delete();

        return to_route('admin.trips.index')->with('type', 'danger')->with('type', 'message', "{$trip->title} eliminato con successo!");
    }
}
