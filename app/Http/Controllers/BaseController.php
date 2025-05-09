<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * success response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSuccessJson($data, $message, $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }
  
    /**
     * error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendErrorJson($message, $errors = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];
        if(!empty($errors)){
            $response['data'] = $errors;
        }
        return response()->json($response, $code);
    }
}
