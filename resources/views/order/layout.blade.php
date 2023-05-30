@extends('layout')
@section('head')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <style>
        #table-order .fixed-right {
            position: sticky;
            right: -1px;
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
            @yield('table-data')
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
    <script src="{{ asset('assets/js/order.js?i=' . time()) }}" type="module"></script>
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
    @yield('script.order')
@endsection
