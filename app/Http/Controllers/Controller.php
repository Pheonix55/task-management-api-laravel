<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function successResponse($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    protected function errorResponse($data = null, $message = 'Error', $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
