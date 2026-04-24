<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RfidCardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\StudentController;

Route::post('/rfid-check', [RfidCardController::class, 'check']);
Route::post('/log-access', [LogController::class, 'store']);
Route::post('/face-verify', [StudentController::class, 'verifyFace']);
Route::get('/students', [StudentController::class, 'index']);
