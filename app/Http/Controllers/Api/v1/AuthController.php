<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Api\v1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|unique:users,name',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string',
            'confirmPassword'=>'required|same:password'
        ]);

        if ($validator->fails()){
            return $this::errorResponse(422,$validator->messages());
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        $token = $user->createToken('myApp')->plainTextToken;

        return $this::successResponse(201,['user'=>$user,'token'=>$token],);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|string',
        ]);

        if ($validator->fails()){
            return $this::errorResponse(422,$validator->messages());
        }

        $user = User::where('email',$request->email)->first();

        if (!$user){
            return $this::errorResponse(401,'user not found');
        }

        if (Hash::check($user->password,$request->password)){
            return $this::errorResponse(401,'incorrect password');
        };

        $token = $user->createToken('myApp')->plainTextToken;

        return $this::successResponse(201,['user'=>$user,'token'=>$token],);
    }
}
