<?php

use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


//Auth routes
Route::post('/v1/register',[\App\Http\Controllers\Api\v1\AuthController::class,'register']);
Route::post('/v1/login',[\App\Http\Controllers\Api\v1\AuthController::class,'login']);
Route::post('v1/logout',[\App\Http\Controllers\Api\v1\AuthController::class,'logout'])->middleware('auth:sanctum');


Route::group(['prefix'=>'/v1/admin','middleware' => ['auth:sanctum']], function(){
    //Brand routes
    Route::apiResource('/brands',\App\Http\Controllers\Api\v1\BrandController::class);
    Route::get('/brands/{brand}/products',[\App\Http\Controllers\Api\v1\BrandController::class,'products']);

    //Category routes
    Route::apiResource('/categories',\App\Http\Controllers\Api\v1\CategoryController::class);
    Route::get('/categories/{category}/subcategories',[\App\Http\Controllers\Api\v1\CategoryController::class,'subcategory']);
    Route::get('/categories/{category}/products',[\App\Http\Controllers\Api\v1\CategoryController::class,'products']);

    //Product routes
    Route::apiResource('/products',\App\Http\Controllers\Api\v1\ProductController::class);

});


//Payment routes
Route::post('/payment/send',[\App\Http\Controllers\Api\v1\PaymentController::class,'sendInfoToGateway']);
Route::post('/payment/verify',[\App\Http\Controllers\Api\v1\PaymentController::class,'verifyTrans']);
