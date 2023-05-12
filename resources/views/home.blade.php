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
                    <th class="text-center" style="width: 150px;">Mã đơn hàng</th>
                    <th class="text-center">Người Order</th>
                    <th class="text-center">Sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">Đơn giá</th>
                    <th class="text-center" style="width: 15%;">Trạng thái</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <th class="text-center" scope="row">{{ $order->code }}</th>
                        <td class="text-center">{{ $order->user->fullname }}</td>
                        <td class="text-center">{{ $order->product->title }}</td>
                        <td class="text-center">{{ $order->quantity }}</td>
                        <td class="text-center">{{ number_format($order->total_price) }}</td>
                        <td class="text-center">{!! $order->statusBadge() !!}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip"
                                        title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip"
                                        title="Delete">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
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
@endsection