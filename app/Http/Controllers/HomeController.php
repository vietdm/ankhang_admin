<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Users;
use App\Models\Withdraw;
use App\Utils\UserUtil;
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

    public function withdraw(): View|Application|Factory
    {
        $withdraws = Withdraw::with(['user', 'bank'])->orderByDesc('id')->get();
        return view('withdraw', compact('withdraws'));
    }

    public function historySales($username)
    {
        $user = Users::whereUsername($username)->first();
        if (!$user) {
            return view('history_sale');
        }
        UserUtil::getTotalChildAndSale($user->phone, $total, $totalSale, $dataTotalSale);
        dd($dataTotalSale);
    }
}
