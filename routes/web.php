<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;


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

Route::get('/', function () {
    return view('welcome');
});

//php artisan serve can not handle sending a request from web route to api route,so use server or localhost like XAMPP and make needed changes in htdocs/yourProject/routes/web.php
Route::get('/payment/verify',function(Request $request){
    $response = Http::post('http://localhost:7878/api/payment/verify',[
        'token'=>$request->token,
    ]);
    dd($response->body());
});
