@extends('layout')
@section('head')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection
@section('content')
    <div class="row gutters-tiny invisible" data-toggle="appear">
        <div class="col-12">
            <div class="alert alert-secondary">
                <h3 class="mb-0 text-primary">Danh sách mua hàng</h3>
            </div>
        </div>
        <div class="col-12">
            <table class="table table-bordered table-responsive-md table-striped table-vcenter js-dataTable-full">
                <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">ID</th>
                    <th class="text-center no-sort" style="width: 150px;">Mã đơn hàng</th>
                    <th class="text-center no-sort">Họ Tên</th>
                    <th class="text-center no-sort">Sản phẩm</th>
                    <th class="text-center no-sort">Số lượng</th>
                    <th class="text-center">Đơn giá</th>
                    <th class="text-center" style="width: 100px">Trạng thái</th>
                    <th class="text-center" style="width: 100px">Thanh toán</th>
                    <th class="text-center no-sort" style="width: 100px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <th class="text-center" scope="row">{{ $order->id }}</th>
                        <th class="text-center">{{ $order->code }}</th>
                        <td class="text-center" data-search="{{ convert_vi_to_en($order->user->fullname) . ' ' . $order->user->fullname }}">
                            {{ $order->user->fullname }}
                        </td>
                        <td class="text-center">{{ $order->product->title }}</td>
                        <td class="text-center">{{ $order->quantity }}</td>
                        <td class="text-center">{{ number_format($order->total_price) }}</td>
                        <td class="text-center td-status-badge" style="width: 100px">{!! $order->statusBadge() !!}</td>
                        <td class="text-center td-status-pay" style="width: 100px">
                            @if($order->payed === 0)
                                <span class="badge badge-danger">Chưa thanh toán</span>
                            @else
                                <span class="badge badge-success">Đã thanh toán</span>
                            @endif
                        </td>
                        <td class="text-center" style="width: 200px">
                            @if($order->isCreated())
                                @if($order->payed === 0)
                                    <button type="button" class="m-1 btn btn-created btn-primary" onclick="Order.payed({{ $order->id }}, this)">
                                        Xác nhận thanh toán
                                    </button>
                                @endif
                                <button type="button" class="m-1 btn btn-created btn-success" onclick="Order.accept({{ $order->id }}, this)">
                                    Kích hoạt đơn hàng
                                </button>
                                <button type="button" class="m-1 btn btn-created btn-outline-danger" onclick="Order.cancel({{ $order->id }}, this)">
                                    Hủy
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/be_tables_datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/order.js') }}" type="module"></script>
@endsection