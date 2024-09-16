<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\View\View
     */
    public function __invoke()
    {
        // Recupera l'ID dell'utente autenticato
        $userId = Auth::id();

        // Recupera solo le tappe associate all'utente autenticato
        $locations = DB::table('stops')
            // Unisce la tabella 'stops' con la tabella 'days'
            ->join('days', 'stops.day_id', '=', 'days.id')
            // Unisce la tabella 'days' con la tabella 'trips'
            ->join('trips', 'days.trip_id', '=', 'trips.id')
            // Filtra i viaggi per l'utente autenticato
            ->where('trips.user_id', '=', $userId)
            // Seleziona le colonne desiderate
            ->select('stops.latitude', 'stops.longitude', 'stops.title', 'stops.image', 'stops.notes', 'days.date', 'trips.color')
            // Recupera i risultati
            ->get();

        // Passa i dati recuperati alla vista 'admin.home'
        // 'compact' crea un array associativo con la chiave 'locations' e il valore corrispondente
        return view('admin.home', compact('locations'));
    }
}
