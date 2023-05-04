<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function lists()
    {
        return Response::success([
            'products' => Products::all()
        ]);
    }

    public function getOne($id) {
        return Response::success([
            'product' => Products::whereId($id)->first()
        ]);
    }
}
