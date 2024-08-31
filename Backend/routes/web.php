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
Route::get('/', GuestHomeController::class)->name('guest.home');

Route::prefix('/admin')->name('admin.')->middleware('auth')->group(function () {
    // Admin Home route
    Route::get('', AdminHomeController::class)->name('home');

    // Days Admin routes
    Route::resource('days', AdminDayController::class);

    // Stops Admin Routes
    Route::resource('stops', AdminStopController::class)->except('create');
    // Route for creating a Stop within a Trip
    Route::get('trips/{trip}/stops/create', [AdminStopController::class, 'create'])->name('stops.create');

    // Trips Admin routes
    Route::resource('trips', AdminTripController::class);
});

// Profile routes 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
