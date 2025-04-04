<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Post\PostController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::get('/pay', [PaymentController::class, 'makePayment']);

Route::post('/register', [RegisterController::class, 'register']);




Route::get("/checkage", function (Request $request) {
    return response()->json(["message" => "You are authorized"]);
})->middleware('checkage:21');

Route::post('/login', [LoginController::class, 'login'])->middleware('log.execution.time');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'create']);
        Route::put('/{id}', [PostController::class, 'update']);
    });
    Route::post('/edit-profile', [RegisterController::class, 'editProfile']);
});
