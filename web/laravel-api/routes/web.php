<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::get('/admin', function () {
    return view('admin.index');
});
