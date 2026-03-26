<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SystemUserController;
use App\Http\Controllers\Api\DeviceApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rota para buscar usuários do sistema por card (protegida por autenticação) 
Route::middleware(['auth.any'])->get('/system-users/{card}/logins', [SystemUserController::class, 'getLoginsByCard']);

// Mapas de Rede — dispositivos no mapa ativo (tipo: SEAT, PRINTER, …)
Route::get('/map-devices/{type}/{code}', [DeviceApiController::class, 'show']);
Route::get('/map-devices/seats/occupied', [DeviceApiController::class, 'occupiedSeats']);
 