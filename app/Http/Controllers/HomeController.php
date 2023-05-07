<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $orders = Orders::with(['user'])->get();
        return view('home', compact('orders'));
    }
}
