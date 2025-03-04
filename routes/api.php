<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomerAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customers/{customer_no}', [CustomerAPIController::class, 'show']);
    Route::put('/customers/{customer_no}', [CustomerAPIController::class, 'update']);
    Route::post('/customers', [CustomerAPIController::class, 'store']);
});
