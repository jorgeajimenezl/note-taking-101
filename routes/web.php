<?php

use App\Http\Controllers\Api\ContributorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TokenController;
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
    Route::resource('/tags', TagController::class)->except(['edit', 'update', 'show']);
    Route::post('/tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('tasks.toggle-complete');

    // Token-based API authentication
    Route::post('/tokens', [TokenController::class, 'store'])->name('tokens.store');
    Route::delete('/tokens/{token}', [TokenController::class, 'destroy'])->name('tokens.destroy');
    Route::get('/tokens', [TokenController::class, 'index'])->name('tokens.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('api/contributors', [ContributorController::class, 'update'])->name('contributors.update');
});

Route::passkeys();

require __DIR__.'/auth.php';
