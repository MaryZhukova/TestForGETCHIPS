<?php
namespace App\Http\Controllers\Api\V1;
use App\Helpers\ApiResponseHelpers;
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
    use ApiResponseHelpers;

    /**
     * @param LoginAuthRequest $request
     */
    public function login(LoginAuthRequest $request){

        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return $this->jsonErrorAuth(__('auth.failed'));
        }

        $user = User::where('email', $request->email)->first();
        return $this->jsonSuccess([
                'token' => $user->createToken("API_TOKEN")->plainTextToken,
                'token_type' => 'Bearer'
            ]);
    }


    /**
     * @param RegisterAuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterAuthRequest $request, UserService $userService)
    {
        $newUser = $userService->createNewUser($request->validated());
        return $this->jsonSuccess([
            'access_token' => $newUser["access_token"],
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function logout(Request $request)
    {

        auth()->user()->tokens()->delete();
        $request->user()->currentAccessToken()->delete();
        return $this->jsonSuccess([]);
    }


}
