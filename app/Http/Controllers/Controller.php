<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

/**
*   @OA\Info(
*       title="APIs For Vitas Project",
*       version="1.0.0",
*   ),
*   @OA\SecurityScheme(
 *       securityScheme="bearerAuth",
 *       in="header",
 *       name="bearerAuth",
 *       type="http",
 *       scheme="bearer",
 *       bearerFormat="JWT",
 *    ),
*/
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sentSuccessResponse($data, $message, $status_code) {

        return response()->json([
            'data' => $data,
            'message' => $message,
            'status_code' => $status_code
        ], Response::HTTP_OK);
    }

    public function sentFailureResponse($status_code, $message) {

        return response()->json([
            'message' => $message,
            'status_code' => $status_code
        ], $status_code);
    }
}
