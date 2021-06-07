<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Classes\Helper;
use Illuminate\Http\Request;
use Auth;
class UserController extends Controller
{
    //
    public function signup(Request $request)
    {
      
        $user = new User([
            'hoten' => $request->hoten,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function login(Request $request)
    {
        $login = [
            'email' => request('email'),
            'password' => request('password'),
        ];
        if(Auth::attempt($login)){
            $user = $request->user();
            $user->anhdaidien = Helper::$URL.$user->anhdaidien;
            $tokenResult = $user->createToken('LD Mobile');
            $token = $tokenResult->token;
            $token->save();
            return response()->json([
                'status' => true,
                'code'  => 200,
                'message'=> null,
                'data' => $user,
                'token' => $tokenResult->accessToken,
            ]);
        }
            return response()->json([
                'status' => false,
                'code'  => 401,
                'message' => 'Username or Password not valid'
            ]);
        
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
