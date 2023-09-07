<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($message, $data = [], $code)
    {
    	$response = array_merge([
            'success' => true,
            'message' => $message,
        ], $data);

        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = array_merge([
            'success' => false,
            'message' => $error,
        ], $errorMessages);

        return response()->json($response, $code);
    }
}
