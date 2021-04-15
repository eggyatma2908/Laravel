<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LandingPageController::class, 'index'])->name('index');

// product
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/product/detail', [ProductController::class, 'detail'])->name('products.details');

// cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');

// auth
Route::get('/auth', [AuthController::class, 'index'])->name('auth');
