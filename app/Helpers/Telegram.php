<?php

namespace App\Helpers;

use Exception;
use GuzzleHttp\Client;

class Telegram
{
    const CHAT_WITHDRAW = env('CHAT_WITHDRAW');
    const CHAT_STORE = env('CHAT_STORE');
    const CHAT_CHECK_STORE = env('CHAT_CHECK_STORE');
    const CHAT_REPORT_BUG = env('CHAT_REPORT_BUG');

    const BOT_TOKEN_DEFAULT = env('BOT_TOKEN_DEFAULT');
    const BOT_TOKEN_REPORT_BUG = env('BOT_TOKEN_REPORT_BUG');

    public static function pushMgs($mgs, $chat_id, $bot_token = self::BOT_TOKEN_DEFAULT)
    {
        try {
            $client = new Client([
                "base_uri" => "https://api.telegram.org",
            ]);

            $response = $client->request("GET", "/bot$bot_token/sendMessage", [
                "query" => [
                    "chat_id" => $chat_id,
                    "text" => $mgs,
                    "parse_mode" => "html"
                ]
            ]);

            $body = $response->getBody();
            $arr_body = json_decode($body);

            return $arr_body->ok;
        } catch (Exception $e) {
            ReportHandle($e);
            return false;
        }
    }

    public static function getUpdates($bot_token = self::BOT_TOKEN_DEFAULT)
    {
        $client = new Client([
            "base_uri" => "https://api.telegram.org",
        ]);

        $response = $client->request("POST", "/bot$bot_token/getUpdates");

        $body = $response->getBody();
        $arr_body = json_decode($body);

        dd($arr_body);
    }
}
