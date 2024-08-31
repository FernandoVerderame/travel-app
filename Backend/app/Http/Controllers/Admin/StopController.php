<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    public function create(Trip $trip)
    {
        $stop = new Stop();

        return view('admin.stops.create', compact('stop', 'trip'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([], []);

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

        $stop->save();

        $tripId = $request->input('trip_id');

        return to_route('admin.trips.show', $tripId)->with('type', 'success')->with('message', 'Nuovo tappa creata con successo!');
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
