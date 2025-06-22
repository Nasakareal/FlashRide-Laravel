<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RideController;
use App\Http\Controllers\API\EmergencyController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\VehicleController;
use App\Http\Controllers\API\DriverController;

// Ruta de depuración: obtener usuario autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// 1) AUTENTICACIÓN PÚBLICA
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);


// 2) RUTAS PROTEGIDAS POR TOKEN (Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // 2.1) Perfil y seguridad
    Route::get('/profile',           [AuthController::class, 'profile']);
    Route::post('/change-password',  [AuthController::class, 'changePassword']);


    // 2.2) GESTIÓN DE CHOFERES (DRIVERS)
    Route::get('/drivers',                [AuthController::class, 'listDrivers']);
    Route::post('/drivers',               [AuthController::class, 'registerDriver']);
    Route::get('/drivers/{id}/details',   [DriverController::class, 'details']);
    Route::put('/drivers/{id}',           [DriverController::class, 'update']);
    Route::delete('/drivers/{id}',        [DriverController::class, 'destroy']);

    
    // 2.3) GESTIÓN DE VEHÍCULOS
    Route::get('/vehicles',  [VehicleController::class, 'index']);
    Route::post('/vehicles', [VehicleController::class, 'store']);

    
    // 2.4) GESTIÓN DE VIAJES (RIDES)
    Route::get('/rides',                 [RideController::class, 'index']);
    Route::post('/rides',                [RideController::class, 'store']);
    Route::get('/rides/pending',         [RideController::class, 'pendingRides']);
    Route::get('/rides/{id}',            [RideController::class, 'show']);
    Route::put('/rides/{id}',            [RideController::class, 'update']);
    Route::post('/rides/{id}/accept',    [RideController::class, 'accept']);
    Route::post('/rides/{id}/complete',  [RideController::class, 'complete']);
    Route::post('/rides/{id}/fase', [RideController::class, 'updateFase']);
    Route::post('/rides/estimate', [RideController::class, 'estimateCost']);


    // 2.5) ACTUALIZACIÓN DE UBICACIÓN DEL CONDUCTOR
    Route::post('/location/update', [RideController::class, 'updateLocation']);
    Route::post('/location/global', [RideController::class, 'updateGlobalLocation']);
    Route::get('/drivers/nearby',   [RideController::class, 'nearbyDrivers']);

     // 2.6) MARCAR CONDUCTOR ONLINE/OFFLINE
    Route::post('/users/online',  [AuthController::class, 'markOnline']);
    Route::post('/users/offline', [AuthController::class, 'markOffline']);



    // 2.7) PAGOS
    Route::post('/payments', [PaymentController::class, 'store']);


    // 2.8) BOTÓN DE PÁNICO / EMERGENCIA
    Route::post('/panic', [EmergencyController::class, 'store']);
});
