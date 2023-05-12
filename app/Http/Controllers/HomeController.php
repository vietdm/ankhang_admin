<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(): View|Application|Factory
    {
        $orders = Orders::with(['user', 'product'])->orderByDesc('id')->get();
        return view('home', compact('orders'));
    }
}
