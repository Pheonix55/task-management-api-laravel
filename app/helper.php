<?php

if (!function_exists('apiResponse')) {
    function apiResponse($data = null, string $message = 'OK', int $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}

