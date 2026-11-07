<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('home');


Route::get('questions', [QuestionController::class, 'index'])->name('questions.index');
Route::get('questions/create', [QuestionController::class, 'create'])->name('questions.create') ->middleware('auth'); 
Route::post('questions', [QuestionController::class, 'store'])->name('questions.store')->middleware('auth');

Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit')->middleware('auth');
Route::put('questions/{question}', [QuestionController::class, 'update'])
->name('questions.update')
->middleware('auth', 'can:update,question');

Route::get('questions/{question}', [QuestionController::class, 'show'])->name('questions.show');
Route::delete('questions/{question}', [QuestionController::class, 'destroy'])
->name('questions.destroy')
->middleware('auth', 'can:delete,question');
/* Route::view('/', 'welcome')->name('home'); */
Route::post('answer/{question}', [AnswerController::class, 'storeAnswer'])->name('answer.store')->middleware('auth');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
