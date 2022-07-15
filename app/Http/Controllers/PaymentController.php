<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function sendInfoToGateway(){
        $api = 'test';
        $amount = 100000;
        $mobile = "شماره موبایل";
        $factorNumber = "شماره فاکتور";
        $description = "توضیحات";
        $redirect = 'http://localhost:7878/payment/verify';
        $result = $this->send($api, $amount, $redirect, $mobile, $factorNumber, $description);
        $result = json_decode($result);
        if($result->status) {
            $go = "https://pay.ir/pg/$result->token";
            header("Location: $go");
        } else {
            echo $result->errorMessage;
        }
    }

    public function send($api, $amount, $redirect, $mobile = null, $factorNumber = null, $description = null) {
        return curl_post('https://pay.ir/pg/send', [
            'api'          => $api,
            'amount'       => $amount,
            'redirect'     => $redirect,
            'mobile'       => $mobile,
            'factorNumber' => $factorNumber,
            'description'  => $description,
        ]);
    }
}
