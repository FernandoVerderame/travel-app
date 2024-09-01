<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Stop;
use App\Models\Trip;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    public function create(Trip $trip, Day $day)
    {
        $stop = new Stop();

        return view('admin.stops.create', compact('stop', 'trip', 'day'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            [
                'title' => 'required|string|min:5|max:50|unique:stops',
                'image' => 'nullable|image|mimes:png,jpg,jpeg',
                'foods' => 'nullable|string',
                'address' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'day_id' => 'required|exists:days,id'
            ]
        ], [
            'title.required' => 'Il titolo è obbligatorio',
            'title.min' => 'Il titolo deve essere di almeno :min caratteri',
            'title.max' => 'Il titolo deve essere di un massimo di :max caratteri',
            'title.unique' => 'Non possono esserci due tappe con lo stesso titolo',
            'image.image' => 'Il file aggiunto non è un\'immagine',
            'image.mimes' => 'Le estensioni valide sono .png, .jpg, .jpeg',
            'foods.string' => 'I piatti tipici devono essere una stringa di testo',
            'address.required' => 'L\'indirizzo è obbligatorio',
            'address.string' => 'L\'indirizzo deve essere una stringa di testo',
            'latitude.required' => 'La latitudine è obbligatoria',
            'latitude.numeric' => 'La latitudine deve essere un numero valido',
            'longitude.required' => 'La longitudine è obbligatoria',
            'longitude.numeric' => 'La longitudine deve essere un numero valido',
            'day_id.required' => 'Il giorno è obbligatorio',
            'day_id.exists' => 'Il giorno selezionato non esiste'
        ]);

        $data = $request->all();

        $stop = new Stop();

        $stop->fill($data);
        $stop->slug = Str::slug($stop->title);

        // New file check
        if (Arr::exists($data, 'image')) {
            $extension = $data['image']->extension();

            // Save URL
            $img_url = Storage::putFileAs('stop_images', $data['image'], "$stop->slug.$extension");
            $stop->image = $img_url;
        }

        $stop->day_id = $request->input('day_id');

        $stop->save();

        $trip = Trip::findOrFail($request->input('trip_id'));

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
    public function edit(Stop $stop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stop $stop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stop $stop)
    {
        //
    }
}
