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
                        <th class="text-center">#</th>
                        <th class="text-center">Username</th>
                        <th class="text-center no-sort">Họ Tên</th>
                        <th class="text-center">Số tiền rút</th>
                        <th class="text-center no-sort">Số tiền thực nhận</th>
                        <th class="text-center no-sort">Thông tin thanh toán</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center no-sort" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($withdraws as $withdraw)
                        <tr>
                            <td class="text-center" style="min-width: 80px">
                                {{ \Carbon\Carbon::parse($withdraw->created_at)->format('Y-m-d H:i:s') }}
                            </td>
                            <th class="text-center">{{ $withdraw->user->username }}</th>
                            <th class="text-center">{{ $withdraw->user->fullname }}</th>
                            <td class="text-center">{{ number_format($withdraw->money) }}</td>
                            <td class="text-center">{{ number_format($withdraw->money_real) }}</td>
                            <td class="text-center">
                                Ngân hàng: <b>{{ $withdraw->bank->short_name }}</b>
                                <br>
                                STK: <b>{{ $withdraw->account_number }}</b>
                                <br>
                                Chi nhánh: <b>{{ $withdraw->branch }}</b>
                            </td>
                            <td class="text-center td-status">
                                @if ($withdraw->status === 0)
                                    <span class="badge badge-warning">Chưa xác nhận</span>
                                    <div class="text-primary w-100 mt-2" style="cursor: pointer;"
                                        data-code="{{ strtolower($withdraw->bank->code) }}"
                                        data-account="{{ $withdraw->account_number }}"
                                        data-money="{{ $withdraw->money_real }}"
                                        data-username="{{ $withdraw->user->username }}" onclick="Withdraw.openQr(this)">
                                        Lấy QR chuyển tiền
                                    </div>
                                @elseif ($withdraw->status === 1)
                                    <span class="badge badge-primary">Đã xác nhận</span>
                                @elseif ($withdraw->status === 3)
                                    <span class="badge badge-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($withdraw->isCreated())
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
    <div class="modal fade" id="modalQrCodePayment" tabindex="-1" role="dialog" aria-labelledby="modalQrCodePayment"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">QR Thanh toán</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content text-center">
                        <img src="#" alt="img" style="width: 100%; max-width: 95vw">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/be_tables_datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/withdraw.js?i=2') }}" type="module"></script>
@endsection
