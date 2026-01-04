<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

// Emergency Setup Route
Route::get('/setup-final', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return 'Database Seeded! Admin, Teacher, and Student created. Go to /admin and use password "password"';
});