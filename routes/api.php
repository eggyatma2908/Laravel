<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CartController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/products/store', [ProductController::class, 'store']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/users/get-all', [UserController::class, 'getAll']);
    
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/cart/checkout', [CartController::class, 'checkout']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']);
});