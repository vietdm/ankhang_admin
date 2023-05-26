@php
    use Carbon\Carbon;
@endphp

Có đơn hàng mới!
==============
Thời gian: {{ Carbon::now()->format('Y-m-d H:i:s') }}
Họ tên: {{ $order->name }}
Username: {{ $user->username }}
Số điện thoại: {{ $order->phone }}
Địa chỉ: {{ $order->address }}
Ghi chú: {{ $order->note }}
==============
@foreach ($requestOrder as $or)
Tên sản phẩm: {{ $or['product']->title }}
Số lượng: {{ $or['quantity'] }}
==============
@endforeach
Tổng giá: {{ number_format($order->total_price) }}
@if (!empty($isPoint) && $isPoint === true)
==============
Sản phẩm đổi bằng điểm
@endif
