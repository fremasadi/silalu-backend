<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\TrafficReportController;
use App\Http\Controllers\TrafficConfirmationController;
use App\Http\Controllers\KecamatanController;

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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/traffic', [TrafficController::class, 'index']);
Route::get('/apill-damage-stats', [TrafficReportController::class, 'apillDamageStats']);

Route::get('/kecamatan', [KecamatanController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/traffic-reports', [TrafficReportController::class, 'store']);
    Route::put('/traffic-reports/{id}/complete', [TrafficConfirmationController::class, 'markAsCompleted']);
    Route::post('/traffic', [TrafficController::class, 'store']);

    Route::get('/traffic-reports', [TrafficReportController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/traffic-reports/{id}/confirm', [TrafficConfirmationController::class, 'confirm']);

});