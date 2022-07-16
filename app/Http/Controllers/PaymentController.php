<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\v1\ApiController;
use http\Client\Response;
use Illuminate\Http\Request;

class PaymentController extends ApiController
{
    public function sendInfoToGateway(){
        $api = 'test';
        $amount = 100000;
        $mobile = "شماره موبایل";
        $factorNumber = "شماره فاکتور";
        $description = "توضیحات";
        //cause php artisan serve can not handle request from web routes to api routes and need server to handle this we use XAMPP
        $redirect = 'http://localhost/laravelEcommerceSiteApi/public/payment/verify';
        $result = $this->send($api, $amount, $redirect, $mobile, $factorNumber, $description);
        $result = json_decode($result);
        if($result->status) {
            $go = "https://pay.ir/pg/$result->token";
            return $this::successResponse(200,['url'=>$go]);
        } else {
            return $this::errorResponse(422,'error has been occurred during connecting to gateway ');
        }
    }

    public function verifyTrans(Request $request){
        $api = 'test';
        $token = $request->token;
        $result = json_decode($this->verify($api,$token));
        //return response()->json($result);
        return $result;
        if(isset($result->status)){
            if($result->status == 1){
                echo "<h1>تراکنش با موفقیت انجام شد</h1>";
            } else {
                echo "<h1>تراکنش با خطا مواجه شد</h1>";
            }
        } else {
            if($_GET['status'] == 0){
                echo "<h1>تراکنش با خطا مواجه شد</h1>";
            }
        }
    }

    function verify($api, $token) {
        return $this->curl_post('https://pay.ir/pg/verify', [
            'api' 	=> $api,
            'token' => $token,
        ]);
    }

    public function send($api, $amount, $redirect, $mobile = null, $factorNumber = null, $description = null) {
        return $this->curl_post('https://pay.ir/pg/send', [
            'api'          => $api,
            'amount'       => $amount,
            'redirect'     => $redirect,
            'mobile'       => $mobile,
            'factorNumber' => $factorNumber,
            'description'  => $description,
        ]);
    }

    function curl_post($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
}
