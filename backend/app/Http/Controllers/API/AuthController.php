<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {

            return new Response([
                'success' => false,
                'status' => 401,
                'message' => __('auth.failed')
            ]);
        }

        $token = $request->user()->createToken('Token Personal')->accessToken;

        return response()->success([
            'access_token'  => $token->accessToken,
            'token_type'    => 'Bearer',
            'expires_at'    => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ]);


    }


    public function create(){
        $userFirst = new User();
        $userFirst->email = "user_1@example.com";
        $userFirst->name = "user_1@example.com";
        $userFirst->password = bcrypt("password_1");
        $userFirst->save();

        $userFirst = new User();
        $userFirst->email = "user_2@example.com";
        $userFirst->name = "user_2@example.com";
        $userFirst->password = bcrypt("password_2");
        $userFirst->save();
        return "ok";
    }
}
