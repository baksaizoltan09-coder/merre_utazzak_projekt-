<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UtiCelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/regisztracio', [AuthController::class, 'regisztracio']);
Route::post('/bejelentkezes', [AuthController::class, 'bejelentkezes']);
Route::post('/kijelentkezes', [AuthController::class, 'kijelentkezes'])->middleware('auth:sanctum');

Route::get('/uti-celok', [UtiCelController::class, 'index']);
Route::get('/uti-celok/{utiCel}', [UtiCelController::class, 'show']);