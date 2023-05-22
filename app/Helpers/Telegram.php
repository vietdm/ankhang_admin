<?php

namespace App\Helpers;

use Exception;
use GuzzleHttp\Client;

class Telegram
{
    const CHAT_WITHDRAW = "-1001867661516";
    const CHAT_STORE = "-1001800834360";
    const CHAT_CHECK_STORE = "-1001900427144";
    const CHAT_REPORT_BUG = "-1001514640947";

    const BOT_TOKEN_DEFAULT = '5320940669:AAHqFlgn0RxP7xCKxhTrRQZRoPxKT0bp5pg';
    const BOT_TOKEN_REPORT_BUG = '6043441821:AAFP7MBo9S6bWviIBGYMuFyzDeDqKxMiaKE';

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
            logger($e->getMessage());
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
