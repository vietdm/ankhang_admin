<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function pushMessageTelegram(Request $request) {
        $mgs = $request->mgs;
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

            if ($arr_body->ok) {
                echo json_encode([
                    'success' => true
                ]);
                exit(1);
            }
            echo json_encode([
                'success' => false
            ]);
            exit(1);
        } catch(Exception $e) {
            echo json_encode([
                'success' => false
            ]);
            exit(1);
        }
    }
}
