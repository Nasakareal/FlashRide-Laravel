<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RideController;
use App\Http\Controllers\API\EmergencyController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\VehicleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Perfil y seguridad
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Viajes
    Route::get('/rides', [RideController::class, 'index']);
    Route::post('/rides', [RideController::class, 'store']);
    Route::get('/rides/pending', [RideController::class, 'pendingRides']);
    Route::get('/rides/{id}', [RideController::class, 'show']);
    Route::put('/rides/{id}', [RideController::class, 'update']);
    Route::post('/rides/{id}/accept', [RideController::class, 'accept']);
    Route::post('/rides/{id}/complete', [RideController::class, 'complete']);

    // Ubicación del conductor
    Route::post('/location/update', [RideController::class, 'updateLocation']);

    // Pagos
    Route::post('/payments', [PaymentController::class, 'store']);

    // Vehículos
    Route::post('/vehicles', [VehicleController::class, 'store']);

    // Emergencias
    Route::post('/panic', [EmergencyController::class, 'store']);
});
