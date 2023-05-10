<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function accepts()
    {
        $orders = Orders::whereStatus(0)->get();
        foreach ($orders as $order) {
            $order->accept();
        }
        echo 'Done!';
        die();
    }
}
