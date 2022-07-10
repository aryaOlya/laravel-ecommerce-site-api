<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public static function successResponse($code,$data,$message=null){
        return response()->json([
            "status"=>'successful',
            "message"=>$message,
            "data"=>$data
        ],$code);
    }

    public static function errorResponse($code, $message=null){
        return response()->json([
            "status"=>'error',
            "message"=>$message,
            "data"=>""
        ],$code);
    }
}
