<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        // Recupera tutte le tappe dal database
        // Esegue una query per ottenere i dati delle tappe, unendo le tabelle 'stops', 'days' e 'trips'
        $locations = DB::table('stops')
            // Unisce la tabella 'stops' con la tabella 'days' usando la chiave esterna 'day_id'
            ->join('days', 'stops.day_id', '=', 'days.id')
            // Unisce la tabella 'days' con la tabella 'trips' usando la chiave esterna 'trip_id'
            ->join('trips', 'days.trip_id', '=', 'trips.id')
            // Seleziona le colonne desiderate da ciascuna tabella
            ->select('stops.latitude', 'stops.longitude', 'stops.title', 'stops.image', 'stops.notes', 'days.date', 'trips.color')
            // Recupera tutti i risultati della query
            ->get();

        // Passa i dati recuperati alla vista 'admin.home'
        // 'compact' crea un array associativo con la chiave 'locations' e il valore corrispondente
        return view('admin.home', compact('locations'));
    }
}
