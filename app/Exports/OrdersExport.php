<?php

namespace App\Exports;

use App\Models\Orders;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;

class OrdersExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    private string $type;

    public function __construct(string $type = 'all')
    {
        $this->type = $type;
    }

    public function collection(): Collection
    {
        $orders = Orders::with(['user', 'product']);
        if ($this->type == 'payed') {
            $orders->where('payed', '1');
        }
        if ($this->type == 'not_pay') {
            $orders->where('payed', '0');
        }
        $orders->orderByDesc('id');
        return $orders->get();
    }

    public function headings(): array
    {
        return [
            'Mã đơn hàng',
            'Sản phẩm mua',
            'Số lượng',
            'Tổng tiền',
            'Địa chỉ',
            'Ghi chú',
            'Username',
            'Họ tên',
            'Ngày mua hàng',
            'Thanh toán',
            'Trạng thái',
        ];
    }

    public function map($row): array
    {
        return [
            $row->code,
            $row->product->title,
            $row->quantity,
            $row->total_price,
            $row->address,
            $row->note,
            $row->user->username,
            $row->user->fullname,
            $row->updated_at,
            $row->payed_text,
            $row->status_text,
        ];
    }

    public function styles($sheet): array
    {
        return [
            1 => ['font' => [
                'bold' => true,
                'size' => 14,
                'name' => 'Times New Roman'
            ]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize(12);
            },
        ];
    }
}
