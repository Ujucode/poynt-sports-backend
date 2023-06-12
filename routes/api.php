<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('addplayer', [ProfileController::class, 'addplayer']);

Route::post('/login', [ProfileController::class, 'login']);