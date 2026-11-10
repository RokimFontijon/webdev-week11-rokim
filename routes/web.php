<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/report');
});

Route::get('/report', [ReportController::class, 'index'])->name('report');

Route::get('/events', [EventController::class, 'index']);

Route::get('/events/filter', [EventController::class, 'filter']);