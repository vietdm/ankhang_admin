@extends('layout')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="block block-bordered block-rounded">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-15">
                            <i class="si si-user fa-3x text-success"></i>
                        </div>
                        <div class="font-size-h4 font-w600">{{ number_format($dashboard->total_user) }}</div>
                        <div class="font-size-h5 font-w600">người dùng</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-bordered block-rounded">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-15">
                            <i class="fa fa-shopping-basket fa-3x text-warning"></i>
                        </div>
                        <div class="font-size-h4 font-w600">{{ number_format($dashboard->total_order) }}</div>
                        <div class="font-size-h5 font-w600">đơn hàng</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-bordered block-rounded block-link" onclick="window.location.href = '/dashboard/bonus'">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-15">
                            <i class="si si-wallet fa-3x text-info"></i>
                        </div>
                        <div class="font-size-h4 font-w600">{{ number_format($dashboard->total_bonus) }}</div>
                        <div class="font-size-h5 font-w600">hoa hồng</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
