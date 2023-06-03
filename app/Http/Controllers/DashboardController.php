<?php

namespace App\Http\Controllers;

use App\Exports\DashboardExport;
use App\Helpers\Response;
use App\Utils\DashboardUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function home()
    {
        $startDate = Carbon::now()->subMonth()->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        $dashboard = DashboardUtil::getDashboard($startDate, $endDate);
        return view('dashboard.index', compact('dashboard'));
    }

    public function bonus(Request $request)
    {
        return view('dashboard.bonus.index');
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';

        if (empty($startDate) || empty($endDate)) {
            return redirect()->to('/');
        }

        if (!validateDate($startDate, 'Y-m-d') || !validateDate($endDate, 'Y-m-d')) {
            return redirect()->to('/');
        }

        $dashboardExport = new DashboardExport($startDate, $endDate);
        $today = Carbon::now()->format('Y-m-d');
        $timestampNow = Carbon::now()->timestamp;
        return Excel::download($dashboardExport, "dashboard-export-$today.$timestampNow.xlsx");
    }

    public function getDashboard(Request $request)
    {
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $format = $request->format === 'true';

        if (empty($startDate) || empty($endDate)) {
            return Response::badRequest('Ngày không chính xác');
        }

        if (!validateDate($startDate, 'Y-m-d') || !validateDate($endDate, 'Y-m-d')) {
            return Response::badRequest('Ngày không đúng định dạng!');
        }

        $dashboard = DashboardUtil::getDashboard($startDate, $endDate, $format);

        return Response::success([
            'dashboard' => $dashboard
        ]);
    }
}
