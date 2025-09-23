<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RideController;
use App\Http\Controllers\API\EmergencyController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\VehicleController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\TransitController;
use App\Http\Controllers\API\DriverVehicleController;
use App\Http\Controllers\API\RouteVehicleController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) { return $request->user(); });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile',          [AuthController::class, 'profile']);
    Route::put ('/profile',          [AuthController::class, 'updateProfile']);
    Route::put ('/profile/email',    [AuthController::class, 'updateEmail']); 
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::get('/drivers',              [AuthController::class, 'listDrivers']);
    Route::post('/drivers',             [AuthController::class, 'registerDriver']);
    Route::get('/drivers/{id}/details', [DriverController::class, 'details'])->whereNumber('id');
    Route::put('/drivers/{id}',         [DriverController::class, 'update'])->whereNumber('id');
    Route::delete('/drivers/{id}',      [DriverController::class, 'destroy'])->whereNumber('id');

    Route::get('/vehicles',  [VehicleController::class, 'index']);
    Route::post('/vehicles', [VehicleController::class, 'store']);

    Route::get('/rides/active',        [RideController::class, 'active']);
    Route::get('/rides',               [RideController::class, 'index']);
    Route::post('/rides',              [RideController::class, 'store']);
    Route::get('/rides/pending',       [RideController::class, 'pendingRides']);
    Route::get('/rides/{id}',          [RideController::class, 'show'])->whereNumber('id');
    Route::put('/rides/{id}',          [RideController::class, 'update'])->whereNumber('id');
    Route::post('/rides/{id}/accept',  [RideController::class, 'accept'])->whereNumber('id');
    Route::post('/rides/{id}/complete',[RideController::class, 'complete'])->whereNumber('id');
    Route::post('/rides/{id}/fase',    [RideController::class, 'updateFase'])->whereNumber('id');
    Route::post('/rides/estimate',     [RideController::class, 'estimateCost']);

    Route::post('/location/update', [RideController::class, 'updateLocation']);
    Route::post('/location/global', [RideController::class, 'updateGlobalLocation']);
    Route::get('/drivers/nearby',   [RideController::class, 'nearbyDrivers']);

    Route::post('/users/online',  [AuthController::class, 'markOnline']);
    Route::post('/users/offline', [AuthController::class, 'markOffline']);

    Route::post('/payments', [PaymentController::class, 'store']);
    Route::post('/panic',    [EmergencyController::class, 'store']);

    Route::post('/transit/vehicles/{id}/ping', [TransitController::class, 'ping'])->whereNumber('id');

    // Chofer ↔ Vehículo
    Route::get ('/vehicles/available',          [DriverVehicleController::class, 'available']);
    Route::get ('/drivers/{driver}/assignment', [DriverVehicleController::class, 'current'])->whereNumber('driver');
    Route::post('/drivers/{driver}/assign',     [DriverVehicleController::class, 'assign'])->whereNumber('driver');
    Route::post('/drivers/{driver}/unassign',   [DriverVehicleController::class, 'unassign'])->whereNumber('driver');

    // Ruta ↔ Vehículo
    Route::get ('/routes/{route}/vehicles',     [RouteVehicleController::class, 'list'])->whereNumber('route');
    Route::post('/routes/{route}/assign',       [RouteVehicleController::class, 'assign'])->whereNumber('route');
    Route::post('/routes/{route}/unassign',     [RouteVehicleController::class, 'unassign'])->whereNumber('route');
    Route::get ('/routes/vehicles/available',   [RouteVehicleController::class, 'availableVehicles']);
});

Route::prefix('transit')->group(function () {
    Route::get('/routes',               [TransitController::class, 'routes']);
    Route::get('/routes/{id}',          [TransitController::class, 'routeShow'])->whereNumber('id');
    Route::get('/routes/{id}/vehicles', [TransitController::class, 'routeVehicles'])->whereNumber('id');
    Route::get('/vehicles',             [TransitController::class, 'vehicles']);
});
