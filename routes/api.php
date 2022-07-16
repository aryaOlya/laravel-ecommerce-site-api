<?php

use Illuminate\Http\Request;
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



Route::group(['prefix'=>'/v1/admin'], function(){
    //Brand roots
    Route::apiResource('/brands',\App\Http\Controllers\Api\v1\BrandController::class);
    Route::get('/brands/{brand}/products',[\App\Http\Controllers\Api\v1\BrandController::class,'products']);

    //Category roots
    Route::apiResource('/categories',\App\Http\Controllers\Api\v1\CategoryController::class);
    Route::get('/categories/{category}/subcategories',[\App\Http\Controllers\Api\v1\CategoryController::class,'subcategory']);
    Route::get('/categories/{category}/products',[\App\Http\Controllers\Api\v1\CategoryController::class,'products']);

    //Product roots
    Route::apiResource('/products',\App\Http\Controllers\Api\v1\ProductController::class);

});


//Payment roots
Route::post('/payment/send',[\App\Http\Controllers\PaymentController::class,'sendInfoToGateway']);
Route::post('/payment/verify',[\App\Http\Controllers\PaymentController::class,'verifyTrans']);
