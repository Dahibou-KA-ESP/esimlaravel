<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller {
    public static function sendSuccess($data = null, $message = null) {
	    return response()->json ( [
				'status'  => 'success',
				'code' 	  => JsonResponse::HTTP_OK,
				'message' => $message,
				'data' 	  => $data,
				'error' 	  => null
		], JsonResponse::HTTP_OK );
	}

    public static function sendError($errorMes, $code = 404,$httpCode=200) {
	    return response()->json([
				'status' => 'error',
				'code' => $code,
				'message' => '',
				'data' => null,
				'error' => [
						$errorMes
				]
	    ], $httpCode );
	}
}
