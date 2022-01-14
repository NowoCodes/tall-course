<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('subscribers', function () {
    return view('subscribers.all');
})->name('subscriber.all');
