<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DBController;
use App\Http\Controllers\FormController;

Route::get('/', function(){
    return view('welcome');
});