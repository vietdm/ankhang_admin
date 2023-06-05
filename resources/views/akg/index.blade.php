@extends('layout')
@section('head')
    <style>
        .heading {
            font-size: 26px;
        }
    </style>
@endsection
@section('content')
    <div class="block">
        <div class="block-header alert-primary">
            <h3 class="block-title font-weight-bold">Tổng quan về điểm AKG</h3>
        </div>
        <div class="block-content pb-3">
            <ul>
                <li>
                    <span class="heading">Tổng: <b>{{ number_format($dashboard->total) }}</b></span>
                </li>
                <li>
                    <span class="heading">Đã tặng: <b>{{ number_format($dashboard->akg_used) }}</b></span>
                </li>
                <li>
                    <span class="heading">Còn lại: <b>{{ number_format($dashboard->akg_total_in_config) }}</b></span>
                </li>
            </ul>
        </div>
    </div>

    <div class="block">
        <div class="block-header alert-primary">
            <h3 class="block-title font-weight-bold">Lịch sử tặng điểm AKG</h3>
        </div>
        <div class="block-content pb-3">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-center no-sort">Ngày</th>
                        <th class="text-center no-sort">Username</th>
                        <th class="text-center no-sort">Họ Tên</th>
                        <th class="text-center no-sort">Số điểm</th>
                        <th class="text-center no-sort">Nội dung</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($histories->count() == 0)
                        <tr>
                            <td colspan="5" class="text-center">Không có lịch sử</td>
                        </tr>
                    @endif
                    @foreach ($histories as $history)
                        <tr>
                            <td class="text-center">{{ $history->date }}</td>
                            <td class="text-center">{{ $history->user->username }}</td>
                            <td class="text-center">{{ $history->user->fullname }}</td>
                            <td class="text-center">{{ number_format($history->amount) }}</td>
                            <td class="text-center">{{ $history->content }}</td>
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
    <script src="{{ asset('assets/js/pages/be_tables_datatables.min.js?i=2') }}"></script>
@endsection
