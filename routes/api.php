<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// User Details Route after logged in
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum')->name('user.details');

// Todo  Routes-> first user must be logged in
Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
Route::get('/todos/{todo}', [TodoController::class, 'show'])->name('todos.show');
// Route::apiResource('todos', TodoController::class)->middleware('auth:sanctum')->names('todos');

// Authenticated Routes Group
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    //todo routes
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');;
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');;
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.delete');;
});