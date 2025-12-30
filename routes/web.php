<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\LoginController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\DriverMediaController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\PanicController;
use App\Http\Controllers\Admin\ItineraryController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SearchController;

// ------------------------------
// Debug (útil mientras configuras)
// ------------------------------
Route::get('/debug-path', fn () => response()->json([
    'path'        => request()->path(),
    'request_uri' => request()->getRequestUri(),
]));

// ------------------------------
// Público
// ------------------------------
Route::get('/', fn () => view('welcome'))->name('home');


// =======================================
// AUTH (SIN /flashride) → /login, /logout
// =======================================
Route::middleware('guest')->group(function () {
    Route::get('login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});


// ------------------------------
// Web app bajo /flashride
// ------------------------------
Route::prefix('flashride')->middleware('auth')->group(function () {

    // Dashboard general (para cualquier usuario autenticado)
    Route::get('dashboard', [DashboardController::class, 'publicDashboard'])->name('dashboard');

    // ============================
    //   ADMIN PANEL (role:admin)
    // ============================
    Route::middleware('role:admin')
        ->prefix('admin')->name('admin.')
        ->group(function () {

            // Dashboard del área Admin
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

            // ========= USUARIOS =========
            Route::resource('users', UserController::class);
            Route::post('users/{user}/activate',   [UserController::class,'activate'])->name('users.activate');
            Route::post('users/{user}/deactivate', [UserController::class,'deactivate'])->name('users.deactivate');
            Route::post('users/bulk',              [UserController::class,'bulk'])->name('users.bulk');
            Route::get('users/export/csv',         [UserController::class,'exportCsv'])->name('users.export.csv');

            // ========= CONDUCTORES =========
            Route::resource('drivers', DriverController::class);
            Route::get('drivers/{driver}/trips',   [DriverController::class,'trips'])->name('drivers.trips');
            Route::post('drivers/{driver}/ban',    [DriverController::class,'ban'])->name('drivers.ban');
            Route::post('drivers/{driver}/unban',  [DriverController::class,'unban'])->name('drivers.unban');
            Route::post('drivers/bulk',            [DriverController::class,'bulk'])->name('drivers.bulk');
            Route::get('drivers/export/csv',       [DriverController::class,'exportCsv'])->name('drivers.export.csv');

            // Archivos del conductor (licencias, pólizas, etc.)
            Route::post('drivers/{driver}/media',           [DriverMediaController::class,'store'])->name('drivers.media.store');
            Route::delete('drivers/{driver}/media/{media}', [DriverMediaController::class,'destroy'])->name('drivers.media.destroy');

            // ========= VEHÍCULOS =========
            Route::resource('vehicles', VehicleController::class);
            Route::post('vehicles/{vehicle}/activate',   [VehicleController::class,'activate'])->name('vehicles.activate');
            Route::post('vehicles/{vehicle}/deactivate', [VehicleController::class,'deactivate'])->name('vehicles.deactivate');
            Route::post('vehicles/bulk',                 [VehicleController::class,'bulk'])->name('vehicles.bulk');
            Route::get('vehicles/export/csv',            [VehicleController::class,'exportCsv'])->name('vehicles.export.csv');

            // ========= ASIGNACIONES Conductor ⇄ Vehículo =========
            Route::resource('assignments', AssignmentController::class)->only(['index','store','destroy']);
            Route::post('assignments/{assignment}/end', [AssignmentController::class,'end'])->name('assignments.end');

            // ========= VIAJES =========
            Route::resource('trips', TripController::class)->only(['index','show','update','destroy']);
            Route::post('trips/{trip}/cancel',  [TripController::class,'cancel'])->name('trips.cancel');
            Route::post('trips/{trip}/finish',  [TripController::class,'finish'])->name('trips.finish');
            Route::get('trips/export/csv',      [TripController::class,'exportCsv'])->name('trips.export.csv');

            // ========= INCIDENTES DE PÁNICO =========
            Route::resource('panic', PanicController::class)->only(['index','show','update']);
            Route::post('panic/{incident}/close', [PanicController::class,'close'])->name('panic.close');

            // ========= ITINERARIOS / RUTAS (camión) =========
            Route::resource('itineraries', ItineraryController::class);
            Route::post('itineraries/{itinerary}/publish',   [ItineraryController::class,'publish'])->name('itineraries.publish');
            Route::post('itineraries/{itinerary}/unpublish', [ItineraryController::class,'unpublish'])->name('itineraries.unpublish');

            // ========= PAGOS / LIQUIDACIONES =========
            Route::resource('payouts', PayoutController::class)->only(['index','show','store']);
            Route::get('payouts/export/csv', [PayoutController::class,'exportCsv'])->name('payouts.export.csv');

            // ========= REPORTES =========
            Route::get('reports',                 [ReportController::class,'index'])->name('reports.index');
            Route::get('reports/kpis',            [ReportController::class,'kpis'])->name('reports.kpis');
            Route::get('reports/download/{type}', [ReportController::class,'download'])->name('reports.download');

            // ========= AJUSTES =========
            Route::get('settings',  [SettingController::class,'index'])->name('settings.index');
            Route::post('settings', [SettingController::class,'store'])->name('settings.store');

            // ========= SEARCH / DATATABLES =========
            Route::get('search/drivers',  [SearchController::class,'drivers'])->name('search.drivers');
            Route::get('search/vehicles', [SearchController::class,'vehicles'])->name('search.vehicles');
            Route::get('search/trips',    [SearchController::class,'trips'])->name('search.trips');
        });
});
