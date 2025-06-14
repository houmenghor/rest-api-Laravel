<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('roles',RoleController::class);
Route::apiResource("brands",BrandController::class);
Route::middleware(['auth:api'])->group(function(){

Route::apiResource('provinces',ProvinceController::class);
Route::apiResource('suppliers',SupplierController::class);
Route::apiResource("payment-methods",PaymentMethodController::class);
Route::apiResource("customers",CustomerController::class);


Route::apiResource("categories",CategoryController::class);
Route::apiResource('products',ProductController::class);
Route::apiResource("auths",AuthController::class);
});

Route::post("auths/login",[AuthController::class,'login']);
