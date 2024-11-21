<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('task.index');
Route::get('/task/{id}', [App\Http\Controllers\TaskController::class, 'show'])->name('task.show');
