<?php


namespace App\Helpers;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseHelpers
{

    public function jsonError($message)
    {
        return \response()->json([
                'status' => 404,
                'success' => false,
                'message' => $message
            ]
        );


    }

    public function jsonErrorAuth($message)
    {
        return response()->json([
                'status' => 401,
                'success' => false,
                'message' => $message
            ]
        );
    }

    public function jsonSuccess($data)
    {
        return response()->json([
            'status' => 200,
            'success' => true,
            'data' => $data ?? ""

        ]);
    }

    public function jsonErrorValidation($errors)
    {
        return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Validation errors',
                'data' => $errors
            ]
        );
    }


}
