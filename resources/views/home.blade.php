@extends('layout')
@section('content')
    <div class="row gutters-tiny invisible" data-toggle="appear">
        <div class="col-12">
            <table class="table table-hover table-vcenter">
                <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th class="text-center">Người Order</th>
                    <th class="text-center">Sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">Đơn giá</th>
                    <th class="text-center" style="width: 15%;">Access</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <th class="text-center" scope="row">{{ $order->id }}</th>
                        <td class="text-center">{{ $order->user->fullname }}</td>
                        <td class="text-center">Disabled</td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                        <td class="text-center">
                            <span class="badge badge-primary">Personal</span>
                        </td>
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
