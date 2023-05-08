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
            echo 'Order không tồn tại!';
            die();
        }
        if ($order->status != 0) {
            echo 'Order đã được accept trước đó!';
            die();
        }
        $order->accept();
        echo 'Done!';
        die();
    }
}
