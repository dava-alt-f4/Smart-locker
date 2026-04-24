<?php

use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::get('/admin', [LogController::class, 'index'])->name('admin');
