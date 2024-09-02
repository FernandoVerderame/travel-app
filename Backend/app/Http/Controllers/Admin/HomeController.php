<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {
        // Recupera tutte le tappe dal database
        $locations = DB::table('stops')
            ->join('days', 'stops.day_id', '=', 'days.id')
            ->join('trips', 'days.trip_id', '=', 'trips.id')
            ->select('stops.latitude', 'stops.longitude', 'stops.title', 'stops.image', 'days.date', 'trips.color')
            ->get();

        return view('admin.home', compact('locations'));
    }
}
