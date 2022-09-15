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

            return \response()->json([
                'success' => false,
                'status' => 401,
                'message' => __('auth.failed')
            ]);
        }

        $user = User::where('email', $request->email)->first();

        return \response()->json([
            'status' => true,
            'massage' => __('auth.login'),
            'token' => $user->createToken("API_TOKEN")->plainTextToken,
            'token_type' => 'Bearer'
        ], 200);
        
    }
}
