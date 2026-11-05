<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route:: get('/', [PageController::class, 'index'])->name('home');

/* Route::view('/', 'welcome')->name('home'); */

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
