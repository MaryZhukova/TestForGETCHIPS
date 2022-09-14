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

        $user = User::where('email', $request->email)->first();
        return \response()->json([
            'status' => true,
            'massage' => 'Logget',
            'token' => $user->createToken("API_TOKEN")->plainTextToken,
            'token_type' => 'Bearer'
        ], 200);


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
