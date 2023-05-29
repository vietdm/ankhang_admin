<?php

namespace App\Exports;

use App\Models\Orders;
use Carbon\Carbon;
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
        $orders = Orders::with(['user', 'product', 'combo.product']);
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
            'Ngày mua hàng',
            'Username',
            'Họ tên',
            'Mã đơn hàng',
            'Sản phẩm mua',
            'Tổng tiền',
            'Địa chỉ',
            'Ghi chú',
            'Thanh toán',
            'Trạng thái',
        ];
    }

    public function map($row): array
    {
        if ($row->product_id != 0) {
            $productTitle = $row->product->title;
            $productQuantity = $row->quantity;
            $textProductBuy = "$productTitle\nSố lượng: $productQuantity";
        } else {
            $textProductBuy = '';
            foreach ($row->combo as $combo) {
                if ($textProductBuy != '') {
                    $textProductBuy .= "\n\n";
                }
                $productTitle = $combo->product->title;
                $productQuantity = $combo->quantity;
                $textProductBuy .= "$productTitle\nSố lượng: $productQuantity";
            }
        }

        return [
            Carbon::parse($row->created_at)->format('Y-m-d H:i:s'),
            $row->user->username,
            $row->user->fullname,
            $row->code,
            $textProductBuy,
            number_format($row->total_price),
            $row->address,
            $row->note,
            $row->payed_text,
            $row->status_text,
        ];
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
                'vertical' => 'top'
            ],
            ...$font()
        ];
        return [
            'A' => [
                ...$defaultStyle
            ],
            'B' => [
                ...$defaultStyle
            ],
            'C' => [
                ...$defaultStyle
            ],
            'D' => [
                ...$defaultStyle
            ],
            'E' => [
                'alignment' => [
                    'vertical' => 'top',
                    'wrapText' => true
                ],
                ...$font()
            ],
            'F' => [
                ...$defaultStyle
            ],
            'G' => [
                ...$defaultStyle
            ],
            'H' => [
                ...$defaultStyle
            ],
            'I' => [
                ...$defaultStyle
            ],
            'J' => [
                ...$defaultStyle
            ],
            1 => [
                ...$font(14, true)
            ]
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {
                $workSheet = $event->sheet->getDelegate();
                $workSheet->freezePane('A2');
            },
        ];
    }
}
