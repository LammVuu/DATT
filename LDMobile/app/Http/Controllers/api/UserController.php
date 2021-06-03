<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class UserController extends Controller
{
    //


    public function login(Request $request)
    {
        $login = [
            'email' => request('email'),
            'matkhau' => request('matkhau'),
        ];
        if(Auth::attempt($login)){
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            return response()->json([
                'status' => 200
                'data' => $user
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
            ]);
        }
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
