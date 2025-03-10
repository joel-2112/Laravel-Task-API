<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// User Details Route after logged in
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Todo Routes
    Route::get('/todos', [TodoController::class, 'getAllTodos']);
    Route::post('/todos', [TodoController::class, 'createTodo']);
    Route::get('/todos/{id}', [TodoController::class, 'getTodoById']);
    Route::put('/todos/{id}', [TodoController::class, 'updateTodoById']);
    Route::delete('/todos/{id}', [TodoController::class, 'deleteTodoById']);

});