<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
  Route::get('me', [AuthController::class, 'me']);
  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('refresh', [AuthController::class, 'refresh']);

  // File routes
  Route::prefix('files')->group(function () {
    Route::post('upload', [FileController::class, 'upload']);
    Route::get('list', [FileController::class, 'list']);
    Route::get('download/{filename}', [FileController::class, 'download']);
    Route::delete('{filename}', [FileController::class, 'delete']);
  });
});