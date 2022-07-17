<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\v1\ApiController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends ApiController
{
    public function sendInfoToGateway(Request $request)
    {
        //echo gettype($request);
        //return $request;
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'order_items' => 'required',
            'order_items.*.product_id' => 'required|integer',
            'order_items.*.quantity' => 'required|integer',
            'request_from' => 'required'
        ]);
        if ($validator->fails()) {
            return $this::errorResponse(422,$validator->messages());
        }

        $totalAmount = 0;
        $deliveryAmount = 0;
        foreach ($request->order_items as $orderItem) {
//            echo gettype($orderItem['product_id']);
//            echo "----";
            //return $orderItem->product_id;
            $product = Product::findOrFail($orderItem['product_id']);
            if ($product->quantity < $orderItem['quantity']) {
                return $this::errorResponse(422,'مقدار وارد شده بیشتر از حد مجاز میباشد');
            }
            $totalAmount += $product->price * $orderItem['quantity'];
            $deliveryAmount += $product->delivery_amount;
        }

        $payingAmount = $totalAmount + $deliveryAmount;

        $amounts = [
            'totalAmount' => $totalAmount,
            'deliveryAmount' => $deliveryAmount,
            'payingAmount' => $payingAmount,
        ];


        $api = 'test';
        $amount = $payingAmount;
        $mobile = "شماره موبایل";
        $factorNumber = "شماره فاکتور";
        $description = "توضیحات";
        //cause php artisan serve can not handle request from web routes to api routes and need server to handle this we use XAMPP
        $redirect = 'http://localhost/laravelEcommerceSiteApi/public/payment/verify';
        $result = $this->send($api, $amount, $redirect, $mobile, $factorNumber, $description);
        $result = json_decode($result);
        if($result->status) {
            OrderController::create($request, $amounts, $result->token);
            $go = "https://pay.ir/pg/$result->token";
            return $this::successResponse(200,['url'=>$go]);
        } else {
            return $this::errorResponse(422,$result->errorMessage);
        }
    }

    public function verifyTrans(Request $request){
        $api = 'test';
        $token = $request->token;
        $result = json_decode($this->verify($api,$token));
        //return response()->json($result);
        //return $result;
        if(isset($result->status)){
            if($result->status == 1){
                if(Transaction::where('trans_id' , $result->transId)->exists()){
                    return $this->errorResponse('این تراکنش قبلا توی سیستم ثبت شده است' , 422);
                }
                OrderController::update($token, $result->transId);
                return $this->successResponse(200, null,'تراکنش با موفقیت انجام شد' );
            } else {
                echo $this::errorResponse(422,'تراکنش با خطا مواجه شد');
            }
        } else {
            if ($request->status == 0) {
                return $this->errorResponse(422 ,'تراکنش با خطا مواجه شد' );
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
