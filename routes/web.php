<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('task.index');
Route::get('/task/{id}', [App\Http\Controllers\TaskController::class, 'show'])->name('task.show');
Route::put('/task/{id}', [App\Http\Controllers\TaskController::class, 'update'])->name('task.update');
Route::delete('/task/{id}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('task.destroy');
Route::post('/task/{task}/toggle-complete', [App\Http\Controllers\TaskController::class, 'toggleComplete']);
