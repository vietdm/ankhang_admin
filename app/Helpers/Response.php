<?php

namespace App\Helpers;

class Response {
    public static function success($data, $code = 200) {
        return response()->json($data, $code);
    }

    public static function badRequest($data) {
        return response()->json($data, 400);
    }

    public static function Unauthorized($data = [])
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
            ...$data
        ], 401);
    }
}
