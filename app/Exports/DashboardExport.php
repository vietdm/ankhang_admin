<?php

namespace App\Exports;

use App\Utils\DashboardUtil;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

class DashboardExport implements FromCollection, WithStyles, ShouldAutoSize
{
    private $startDate;
    private $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $dashboard = DashboardUtil::getDashboard($this->startDate, $this->endDate, true);
        $data = [
            'Người dùng mới' => $dashboard->total_user,
            'Tổng số đơn hàng' => $dashboard->total_order,
            'Tổng hoa hồng' => $dashboard->total_bonus,
            'Tổng doanh số' => $dashboard->total_sale,
            'Tổng tiền rút' => $dashboard->total_withdraw,
        ];
        return collect([
            array_keys($data),
            array_values($data),
        ]);
    }

    public function styles($sheet): array
    {
        $font = fn ($size = 12, $bold = false) => [
            'font' => [
                'bold' => $bold,
                'size' => $size,
                'name' => 'Times New Roman'
            ]
        ];
        $defaultStyle = [
            'alignment' => [
                'vertical' => 'top',
                'horizontal' => 'center'
            ],
            ...$font()
        ];
        return [
            1 => [
                ...$defaultStyle,
                ...$font(14, true)
            ],
            2 => $defaultStyle
        ];
    }
}
