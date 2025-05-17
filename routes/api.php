<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventYearController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;

Route::post('login', [AuthController::class, 'login']);

Route::get('event-years', [EventYearController::class, 'index']);
Route::get('event-years/{id}', [EventYearController::class, 'show']);
Route::post('event-years', [EventYearController::class, 'store']);
Route::put('event-years/{id}', [EventYearController::class, 'update']);
Route::delete('event-years/{id}', [EventYearController::class, 'destroy']);

Route::get('pages', [PageController::class, 'index']);
Route::get('pages/{id}', [PageController::class, 'show']);
Route::post('pages', [PageController::class, 'store']);
Route::put('pages/{id}', [PageController::class, 'update']);
Route::delete('pages/{id}', [PageController::class, 'destroy']);

// File routes
Route::prefix('files')->group(function () {
  Route::post('upload', [FileController::class, 'upload']);
  Route::get('list', [FileController::class, 'list']);
  Route::get('download/{filename}', [FileController::class, 'download']);
  Route::delete('{filename}', [FileController::class, 'delete']);
});


Route::middleware('auth:api')->group(function () {
  Route::get('me', [AuthController::class, 'me']);
  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('refresh', [AuthController::class, 'refresh']);
});