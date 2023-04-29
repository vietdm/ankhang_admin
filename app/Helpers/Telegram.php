<?php

namespace App\Helpers;

class Telegram {
    public static function pushMgs($mgs) {
        try {
            $client = new Client([
                "base_uri" => "https://api.telegram.org",
            ]);

            $bot_token = "5320940669:AAHqFlgn0RxP7xCKxhTrRQZRoPxKT0bp5pg";
            $chat_id = "-1001800834360";
            $response = $client->request("GET", "/bot$bot_token/sendMessage", [
                "query" => [
                    "chat_id" => $chat_id,
                    "text" => $mgs
                ]
            ]);

            $body = $response->getBody();
            $arr_body = json_decode($body);

            return $arr_body->ok;
        } catch(Exception $e) {
            return false;
        }
    }
}