<?php

namespace App\Helpers;

class Response {
    public static function success($data) {
        return response()->json($data);
    }

    public static function badRequest($data) {
        return response()->json($data, 400);
    }
}