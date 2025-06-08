<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\TrafficReportController;

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


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/traffic', [TrafficController::class, 'index']);
    Route::post('/traffic-reports', [TrafficReportController::class, 'store']);

    Route::get('/traffic-reports', [TrafficReportController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);

});