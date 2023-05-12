@extends('layout')
@section('head')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection
@section('content')
    <div class="row gutters-tiny invisible" data-toggle="appear">
        <div class="col-12">
            <div class="alert alert-secondary">
                <h3 class="mb-0 text-primary">Danh sách rút tiền</h3>
            </div>
        </div>
        <div class="col-12">
            <table class="table table-bordered table-responsive-lg table-striped table-vcenter js-dataTable-full">
                <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center no-sort">Họ Tên</th>
                    <th class="text-center no-sort">Số tiền rút</th>
                    <th class="text-center no-sort">Số tiền thực nhận</th>
                    <th class="text-center">Ngày yêu cầu</th>
                    <th class="text-center">Thông tin thanh toán</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center no-sort" style="width: 100px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($withdraws as $withdraw)
                    <tr>
                        <th class="text-center" scope="row">{{ $withdraw->id }}</th>
                        <th class="text-center" style="width: 300px">{{ $withdraw->user->fullname }}</th>
                        <td class="text-center" style="width: 200px">{{ number_format($withdraw->money) }}</td>
                        <td class="text-center" style="width: 200px">{{ number_format($withdraw->money_real) }}</td>
                        <td class="text-center" style="width: 250px">{{ $withdraw->date }}</td>
                        <td class="text-center" style="width: 300px">
                            Ngân hàng: <b>{{ $withdraw->bank->short_name }}</b>
                            <br>
                            STK: <b>{{ $withdraw->account_number }}</b>
                            <br>
                            Chi nhánh: <b>{{ $withdraw->branch }}</b>
                        </td>
                        <td class="text-center td-status" style="width: 150px">
                            @if($withdraw->status === 0)
                                <span class="badge badge-warning">Chưa xác nhận</span>
                            @elseif ($withdraw->status === 1)
                                <span class="badge badge-primary">Đã xác nhận</span>
                            @elseif ($withdraw->status === 3)
                                <span class="badge badge-danger">Đã hủy</span>
                            @endif
                        </td>
                        <td class="text-center" style="width: 200px">
                            @if($withdraw->isCreated())
                                <button type="button" class="m-1 btn btn-created btn-primary"
                                        onclick="Withdraw.accept({{ $withdraw->id }}, this)">
                                    Xác nhận đã chuyển
                                </button>
                                <button type="button" class="m-1 btn btn-danger btn-primary"
                                        onclick="Withdraw.cancel({{ $withdraw->id }}, this)">
                                    Hủy yêu cầu
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
    <script src="{{ asset('assets/js/withdraw.js') }}" type="module"></script>
@endsection
