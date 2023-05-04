<?php

namespace App\Helpers;

use Carbon\Carbon;

function base64url_encode($str): string
{
    return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
}

class JwtHelper
{
    const ALG = 'SHA256';

    private static function keys()
    {
        return env('JWT_KEY', 'TODO');
    }

    public static function encode(array $payload): string
    {
        if (!isset($payload['exp'])) {
            $payload['exp'] = Carbon::now()->addHours(24)->timestamp;
        }

        $headers = [
            'alg' => self::ALG,
            'typ' => 'JWT'
        ];

        $headers_encoded = base64url_encode(json_encode($headers));

        $payload_encoded = base64url_encode(json_encode($payload));

        $signature = hash_hmac(self::ALG, "$headers_encoded.$payload_encoded", self::keys(), true);
        $signature_encoded = base64url_encode($signature);

        return "$headers_encoded.$payload_encoded.$signature_encoded";
    }

    public static function verify(string $token): bool
    {
        // split the jwt
        $tokenParts = explode('.', $token);
        if (count($tokenParts) != 3) return false;

        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
        $expiration = json_decode($payload)->exp;
        $is_token_expired = ($expiration - time()) < 0;

        // build a signature based on the header and payload using the secret
        $base64_url_header = base64url_encode($header);
        $base64_url_payload = base64url_encode($payload);
        $signature = hash_hmac(self::ALG, $base64_url_header . "." . $base64_url_payload, self::keys(), true);
        $base64_url_signature = base64url_encode($signature);

        // verify it matches the signature provided in the jwt
        $is_signature_valid = ($base64_url_signature === $signature_provided);

        return $is_signature_valid && !$is_token_expired;
    }

    public static function decode(string $token) {
        $tokenParts = explode('.', $token);
        $payload = base64_decode($tokenParts[1]);
        return json_decode($payload, 1);
    }
}
