<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class Response {
    public static function success(string|array $data, $code = 200): JsonResponse
    {
        if (gettype($data) == 'string') {
            $data = [
                'message' => $data
            ];
        }
        return response()->json([
            "success" => true,
            ...$data
        ], $code);
    }

    public static function badRequest(string|array $data): JsonResponse
    {
        if (gettype($data) == 'string') {
            $data = [
                'message' => $data
            ];
        }
        return response()->json([
            'success' => false,
            ...$data
        ], 400);
    }

    public static function Unauthorized($data = []): JsonResponse
    {
        if (gettype($data) == 'string') {
            $data = [
                'message' => $data
            ];
        }
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
            ...$data
        ], 401);
    }
}
