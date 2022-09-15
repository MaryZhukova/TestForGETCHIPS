<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\CheckAuthRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register ( Request $request ){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        // Hashing the entered password with default hash
        $request['password'] = bcrypt($request['password']);

        $user = User::create($request->all());

        $token = $user->createToken('API_TOKEN')->plainTextToken;

        return ['message' => 'Registered!' ,  'token' => $token ];
    }



    /**
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        /*$request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);**/

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


    /*public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return \response()->json([
            'status' => true,
            'massage' => __('auth.logout'),
        ], 200);
    }*/
}
