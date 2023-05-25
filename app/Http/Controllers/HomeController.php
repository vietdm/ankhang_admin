<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use App\Models\Users;
use App\Models\Withdraw;
use App\Utils\UserUtil;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home() {
        return view('dashboard.index');
    }

    public function allOrder(): View|Application|Factory
    {
        $orders = Orders::with(['user', 'product', 'combo.product'])->orderByDesc('id')->get();
        return view('order.all', compact('orders'));
    }

    public function transferOrder(): View|Application|Factory
    {
        $orders = Orders::with(['user', 'product', 'combo.product'])->whereStatus(1)->orderByDesc('id')->get();
        return view('order.transfer', compact('orders'));
    }

    public function confirmOrder(): View|Application|Factory
    {
        $orders = Orders::with(['user', 'product', 'combo.product'])->whereStatus(0)->orderByDesc('id')->get();
        return view('order.confirm', compact('orders'));
    }

    public function confirmWithdraw(): View|Application|Factory
    {
        $withdraws = Withdraw::with(['user', 'bank'])->orderByDesc('id')->get();
        return view('withdraw.confirm', compact('withdraws'));
    }

    public function createOrder(): View|Application|Factory
    {
        $products = Products::all();
        return view('create_order', compact('products'));
    }
}
