<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Telegram;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function pushMessageTelegram(Request $request) {
        $mgs = $request->mgs;
        echo json_encode([
            'success' => Telegram::pushMgs($mgs)
        ]);
        exit(1);
    }
}
