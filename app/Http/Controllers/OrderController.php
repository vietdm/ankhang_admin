<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function accept($id)
    {
        $order = Orders::whereId($id)->first();
        if (!$order) {
            dd('Order không tồn tại!');
        }
        $order->accept();
        dd('Done');
    }
}
