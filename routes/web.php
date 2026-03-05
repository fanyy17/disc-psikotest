<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiscController;

Route::get('/', function () {
    return view('DISC.index');
});

Route::get('/disc', [DiscController::class, 'form'])->name('disc.form');

Route::post('/disc/start', [DiscController::class, 'startTest'])->name('disc.start');

Route::get('/disc/test', [DiscController::class, 'test'])->name('disc.test');

Route::post('/disc/store', [DiscController::class, 'store'])->name('disc.store');

Route::get('/disc/result', [DiscController::class, 'result'])->name('disc.result');


use App\Http\Controllers\AdminController;

Route::middleware(['auth'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/{id}', [AdminController::class, 'show'])->name('admin.show');

});