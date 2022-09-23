<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\LoginAuthRequest;
use App\Http\Requests\Api\V1\RegisterAuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\CheckAuthRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Services\UserService;

class AuthController extends Controller
{

    /**
     *
     * @param LoginAuthRequest $request
     * @return Response
     */
    public function login(LoginAuthRequest $request)
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


    /**
     * @param RegisterAuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterAuthRequest $request, UserService $userService)
    {
        $newUser = $userService->createNewUser($request->validated());
        return response()->json([
            'access_token' => $newUser["access_token"],
            'token_type' => 'Bearer',
        ]);
    }


    public function logout(Request $request){

        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }


}
