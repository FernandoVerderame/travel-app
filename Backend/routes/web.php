<?php

use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Guest\HomeController as GuestHomeController;
use App\Http\Controllers\Admin\TripController as AdminTripController;
use App\Http\Controllers\DayController as AdminDayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StopController as AdminStopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Guest home route
// Questa rotta serve la home page per gli utenti non autenticati (guest)
// Usa il controller GuestHomeController e assegna il nome 'guest.home'
Route::get('/', GuestHomeController::class)->name('guest.home');

// Admin routes with authentication and prefix
// Questo gruppo di rotte è protetto dal middleware 'auth' e ha il prefisso '/admin'
// Tutte le rotte all'interno di questo gruppo hanno il prefisso 'admin.' nel loro nome
Route::prefix('/admin')->name('admin.')->middleware('auth')->group(function () {
    // Admin Home route
    // Questa rotta serve la pagina home per gli amministratori
    // Usa il controller AdminHomeController e assegna il nome 'admin.home'
    Route::get('', AdminHomeController::class)->name('home');

    // Days Admin routes
    // Gestisce tutte le operazioni CRUD per i giorni tramite il controller AdminDayController
    Route::resource('days', AdminDayController::class);

    // Stops Admin Routes
    // Gestisce tutte le operazioni CRUD per le tappe tranne le operazioni di creazione e aggiornamento
    Route::resource('stops', AdminStopController::class)->except('create', 'store', 'edit', 'update');
    // Rotte specifiche per la gestione delle tappe all'interno di un viaggio e di un giorno
    // Queste rotte supportano la creazione e l'aggiornamento delle tappe
    Route::get('trips/{trip}/days/{day}/stops/create', [AdminStopController::class, 'create'])->name('stops.create');
    Route::post('trips/{trip}/days/{day}/stops', [AdminStopController::class, 'store'])->name('stops.store');
    Route::get('trips/{trip}/days/{day}/stops/{stop}/edit', [AdminStopController::class, 'edit'])->name('stops.edit');
    Route::put('trips/{trip}/days/{day}/stops/{stop}', [AdminStopController::class, 'update'])->name('stops.update');

    // Trips Admin routes
    // Gestisce tutte le operazioni CRUD per i viaggi tramite il controller AdminTripController
    Route::resource('trips', AdminTripController::class);
});

// Profile routes 
// Rotte protette da autenticazione per la gestione del profilo dell'utente
// Permette di visualizzare, aggiornare o eliminare il profilo dell'utente
Route::middleware('auth')->group(function () {
    // Mostra il modulo di modifica del profilo dell'utente
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Aggiorna le informazioni del profilo dell'utente
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Elimina il profilo dell'utente
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include le rotte per l'autenticazione, come login e registrazione
require __DIR__ . '/auth.php';
