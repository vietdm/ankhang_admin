@extends('order.layout')
@section('table-data')
    <div class="block block-order-transfer">
        <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" data-type="confirmed" href="#list-order-transfer">Đã xác nhận</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-type="deliving" href="#list-order-transfer">Đang vận chuyển</a>
            </li>
        </ul>
        <div class="block-content tab-content overflow-hidden">
            <div class="pb-3 tab-pane fade fade-up" id="list-order-transfer" role="tabpanel">
                <div class="tab-pane-loading text-center">
                    <i class="fa fa-3x fa-cog fa-spin"></i>
                </div>
                <div class="tab-pane-content"></div>
            </div>
        </div>
    </div>
@endsection
@section('script.order')
    <script>
        $('[data-toggle="tabs"]').on('shown.bs.tab', function(e) {
            const el = $(e.target);
            const type = el.attr('data-type');
            Order.loadTranferData(type);
        });
        $(document).ready(() => {
            $('.block-order-transfer').find('.nav-item:first-child .nav-link').trigger('click');
        });
    </script>
@endsection
