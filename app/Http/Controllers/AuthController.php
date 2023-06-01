<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    
   public function register(RegisterRequest $request)  { 
    $validator= Validator::make($request->all(),[
        'email' => 'required|email',
        'password' => 'required|min:6|max:100',
        'confirm_password' => 'required|same:password'
    ]);
    if ($validator->fails()) {
        return response() ->json([
            'errors' =>$validator->errors()
        ],422);
    }
    $user = User::create([
        'email' => $request->email,
        'password' => $request->password,]);

    $token = $user->createToken('API Token')->accessToken;

     return response()->json([
        'token' => $token
    ],201);

    
        
    }

}
   