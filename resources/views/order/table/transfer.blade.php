<table class="table table-bordered table-responsive table-striped table-vcenter js-dataTable-full">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Username</th>
            <th class="text-center no-sort">Họ Tên</th>
            <th class="text-center no-sort">Sản phẩm</th>
            <th class="text-center">Đơn giá</th>
            <th class="text-center" style="width: 100px">Trạng thái</th>
            <th class="text-center no-sort fixed-right" style="width: 100px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            @php
                $fullname = $order->user->fullname ?? '';
                $isCombo = $order->product_id == 0;
            @endphp
            <tr>
                <td class="text-center" style="min-width: 100px">
                    {{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s') }}
                </td>
                <td class="text-center">{{ $order->user->username ?? '' }}</td>
                <td class="text-center" style="min-width: 120px"
                    data-search="{{ convert_vi_to_en($fullname) . ' ' . $fullname }}">
                    {{ $fullname }}
                </td>
                <td style="min-width: 220px">
                    <ul style="padding-left: 15px">
                        @if ($isCombo)
                            @foreach ($order->combo as $combo)
                                <li>
                                    {{ $combo->product->title }}
                                    <br>
                                    Số lượng: {{ $combo->quantity }}
                                </li>
                            @endforeach
                        @else
                            <li>
                                {{ $order->product->title }}
                                <br>
                                Số lượng: {{ $order->quantity }}
                            </li>
                        @endif
                    </ul>
                </td>
                <td class="text-center">{{ number_format($order->total_price) }}</td>
                <td class="text-center td-status-badge" style="width: 100px">
                    {!! $order->statusBadge() !!}
                </td>
                <td class="text-center fixed-right" style="min-width: 90px">
                    @if ($order->isAccepted())
                        <div class="m-1 btn-created text-link text-primary"
                            onclick="Order.deliving({{ $order->id }}, this)">
                            Đánh dấu đang vận chuyển
                        </div>
                    @endif
                    @if ($order->isDeliving())
                        <div class="m-1 btn-created text-link text-success"
                            onclick="Order.setDoneOrder({{ $order->id }}, this)">
                            Đánh dấu đã hoàn thành
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
