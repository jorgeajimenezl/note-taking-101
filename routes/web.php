<?php

use App\Http\Controllers\Api\ContributorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/tasks', TaskController::class)->except(['edit']);
    Route::post('/tasks/{slug}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('tasks.toggle-complete');

    Route::resource('/tags', TagController::class)->except(['edit', 'update', 'show']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('api/contributors', [ContributorController::class, 'update'])->name('contributors.update');
});

require __DIR__.'/auth.php';
