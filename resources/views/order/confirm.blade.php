@extends('layout')
@section('head')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <style>
        #table-order tr>th:last-child,
        #table-order tr>td:last-child {
            position: sticky;
            right: 0;
        }

        #table-order tbody tr:nth-of-type(odd) td {
            background-color: #e7e9eb;
        }

        #table-order thead tr th,
        #table-order tbody tr:nth-of-type(even) td {
            background-color: #f0f2f5;
        }
    </style>
@endsection
@section('content')
    <div class="row gutters-tiny invisible" data-toggle="appear">
        <div class="col-12">
            <div class="alert alert-secondary d-flex justify-content-between">
                <h3 class="mb-0 text-primary">Danh sách mua hàng</h3>
                <div class="area-export d-flex">
                    <select name="export_type" id="export_type" class="form-control">
                        <option value="all" selected>Tất cả</option>
                        <option value="payed">Đã thanh toán</option>
                        <option value="not_pay">Chưa thanh toán</option>
                    </select>
                    <a class="btn btn-primary ml-2 textlink-export" href="/order/export?type=all" style="width: 150px">Xuất
                        Excel</a>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="position-relative">
                <table id="table-order"
                    class="table table-bordered table-responsive table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Username</th>
                            <th class="text-center no-sort">Họ Tên</th>
                            <th class="text-center no-sort">Sản phẩm</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center" style="width: 100px">Trạng thái</th>
                            <th class="text-center" style="width: 100px">Thanh toán</th>
                            <th class="text-center no-sort" style="width: 100px;">Actions</th>
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
                                <td class="text-center td-status-badge" style="width: 100px">{!! $order->statusBadge() !!}</td>
                                <td class="text-center td-status-pay" style="width: 100px">
                                    <div class="area-status-pay">
                                        @if ($order->payed === 0)
                                            <span class="badge badge-warning">Chưa xác nhận</span>
                                        @else
                                            <span class="badge badge-success">Đã thanh toán</span>
                                        @endif
                                    </div>
                                    @if ($order->image_url)
                                        <div class="text-center mt-2">
                                            <div class="text-link btn-show-image-pay" data-image="{{ $order->image_url }}">
                                                Hình ảnh
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center" style="min-width: 90px">
                                    @if ($order->isCreated())
                                        @if ($order->payed === 0)
                                            <div class="m-1 btn-created text-link text-info"
                                                onclick="Order.payed({{ $order->id }}, this)">
                                                Xác nhận thanh toán
                                            </div>
                                        @endif
                                        <div class="m-1 btn-created text-link text-primary"
                                            onclick="Order.accept({{ $order->id }}, this)">
                                            Kích hoạt đơn hàng
                                        </div>
                                        <div class="m-1 btn-created text-link text-danger"
                                            onclick="Order.cancel({{ $order->id }}, this)">
                                            Hủy
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalImagePreview" tabindex="-1" role="dialog" aria-labelledby="modalImagePreview"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Hình ảnh thanh toán</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content text-center">
                        <img src="#" alt="img" style="width: 300px; max-width: 95vw">
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
    <script src="{{ asset('assets/js/pages/be_tables_datatables.min.js?i=2') }}"></script>
    <script src="{{ asset('assets/js/order.js?i=1') }}" type="module"></script>
    <script>
        $('[name="export_type"]').on('change', function() {
            const type = $(this).val();
            $('.textlink-export').attr('href', '/order/export?type=' + type);
        });
        $(document).on('click', '.btn-show-image-pay', function() {
            const imgUrl = $(this).attr('data-image');
            $('#modalImagePreview').find('img').attr('src', imgUrl);
            $('#modalImagePreview').modal();
        });
        $('#modalImagePreview').on('hide.bs.modal', function() {
            $('#modalImagePreview').find('img').attr('src', '#');
        });
    </script>
@endsection