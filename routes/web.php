<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ImportGoogleTasksController;
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
    Route::resource('/tags', TagController::class)->except(['edit', 'update', 'show']);
    Route::post('/tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('tasks.toggle-complete');

    // Token-based API authentication
    Route::post('/tokens', [ApiTokenController::class, 'store'])->name('tokens.store');
    Route::delete('/tokens/{token}', [ApiTokenController::class, 'destroy'])->name('tokens.destroy');
    Route::get('/tokens', [ApiTokenController::class, 'index'])->name('tokens.index');

    Route::get('/import/google-tasks', [ImportGoogleTasksController::class, 'show'])->name('import.google-tasks');
    Route::post('/import/google-tasks', [ImportGoogleTasksController::class, 'store'])->name('import.google-tasks');

    Route::prefix('oauth')->group(function () {
        Route::get('/google/redirect', [GoogleAuthController::class, 'redirect'])->name('oauth.google');
        Route::get('/google/callback', [GoogleAuthController::class, 'callback'])->name('oauth.google.callback');
    });
});

Route::passkeys();

require __DIR__.'/auth.php';
