<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Banks;
use Illuminate\Http\Request;

class BanksController extends Controller
{
    public function list()
    {
        return Response::success([
            'banks' => Banks::all()
        ]);
    }
}
