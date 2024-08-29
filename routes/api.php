<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Task\TaskController;
use App\Http\Middleware\AdminMiddlware;
use App\Http\Middleware\CustomSanctumAdminMiddleware;
use App\Http\Middleware\CustomSanctumMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => [CustomSanctumAdminMiddleware::class]], function (){
    Route::apiResource('tasks',TaskController::class);
});