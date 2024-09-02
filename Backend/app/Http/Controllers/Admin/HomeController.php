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
        $locations = DB::table('stops')->select('latitude', 'longitude', 'title', 'image')->get();

        return view('admin.home', compact('locations'));
    }
}
