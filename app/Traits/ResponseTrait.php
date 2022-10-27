<?php

namespace App\Traits;

use App\Constants\Constants;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait {
    
    /**
     * Send Successful HTTP response
     * @param $message
     * @param  null  $response
     * @return JsonResponse
     */
    public function successResponse($message, $response = null): JsonResponse
    {
        return response()->json([
            'status' => true,
            'code' => Response::HTTP_OK,
            'message' => $message,
            'response' => $response
        ]);
    }

    /**
     * Send Failed HTTP response
     * @param $message
     * @param $response
     * @return JsonResponse
     */
    public function failureResponse($message, $response): JsonResponse
    {
        return response()->json([
            'status' => false,
            'code' => Response::HTTP_FORBIDDEN,
            'message' => $message,
            'response' => $response
        ]);
    }

    /**
     * Send Validation Failure HTTP response
     * @param $message
     * @param $response
     * @return JsonResponse
     */
    public function validationFailure($message, $response): JsonResponse
    {
        return response()->json([
            'status' => false,
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $message,
            'response' => $response
        ]);
    }
}



