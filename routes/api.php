<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgotpassword', [AuthController::class, 'forgotpassword']);

Route::middleware(['auth:sanctum'])->group(function () {

    // customer routes
    Route::get('/customer', [CustomerController::class, 'getCustomer']);
    Route::get('/customer/names', [CustomerController::class, 'getCustomerNames']);
    Route::get('/customer/{id}', [CustomerController::class, 'getCustomerByID']);
    Route::post('/customer', [CustomerController::class, 'addCustomer']);
    Route::patch('/customer', [CustomerController::class, 'updateCustomer']);
    Route::delete('/customer/{id}', [CustomerController::class, 'deleteCustomer']);
    Route::get('/customers/{name}', [CustomerController::class, 'searchCustomerByAll']);

    // order routes
    Route::get('/order', [OrderController::class, 'getAllOrders']);
    Route::get('/order/{id}', [OrderController::class, 'getOrderByID']);
    Route::post('/order', [OrderController::class, 'addOrder']);
    Route::patch('/order', [OrderController::class, 'updateOrder']);
    Route::delete('/order/{id}', [OrderController::class, 'deleteOrder']);
    Route::get('/order/complete/{id}', [OrderController::class, 'completeOrder']);
    Route::get('/order/customer/{id}', [OrderController::class, 'getOrdersByCustomerID']);

    // product routes
    Route::get('/product', [ProductController::class, 'getProducts']);
    Route::get('/product/{id}', [ProductController::class, 'getProductByID']);
    Route::get('/product/name', [ProductController::class, 'getProductNames']);
    Route::post('/product', [ProductController::class, 'addProduct']);
    Route::patch('/product', [ProductController::class, 'updateProduct']);
    Route::delete('/product/{id}', [ProductController::class, 'deleteProduct']);
    Route::get('/product/discontinue/{id}', [ProductController::class, 'discontinueProduct']);
    Route::get('/product/continue/{id}', [ProductController::class, 'continueProduct']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
