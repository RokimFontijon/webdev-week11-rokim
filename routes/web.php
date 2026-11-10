<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/report');
});

Route::get('/report', [ReportController::class, 'index'])->name('report');