<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;



class AuthController extends Controller
{
    
   public function register(RegisterRequest $request)  { 

    $user = User::create($request->validated([
        'email' => $request->email,
        'password' => $request->password,
        'confirm_password' => $request->password,
        ]
    ));

    $token = $user->createToken('API Token')->accessToken;

    return response()->json(['token' => $token], Response::HTTP_CREATED);
        
    }

}
   