<?php

namespace App\Helpers;

use Exception;
use GuzzleHttp\Client;

class Telegram
{
    const CHAT_WITHDRAW = "-1001867661516";
    const CHAT_STORE = "-1001800834360";
    const CHAT_CHECK_STORE = "-1001900427144";

    public static function pushMgs($mgs, $chat_id)
    {
        try {
            $client = new Client([
                "base_uri" => "https://api.telegram.org",
            ]);

            $bot_token = "5320940669:AAHqFlgn0RxP7xCKxhTrRQZRoPxKT0bp5pg";
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
            logger($e->getMessage());
            return false;
        }
    }
}
