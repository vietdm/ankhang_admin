@extends('layout')
@section('head')
    <link rel="stylesheet" href="assets/js/plugins/flatpickr/flatpickr.min.css">
    <style>
        .loading-data {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 13px;
        }

        .loading-data div {
            position: absolute;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #575757;
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }

        .loading-data div:nth-child(1) {
            left: 8px;
            animation: loading-data1 0.6s infinite;
        }

        .loading-data div:nth-child(2) {
            left: 8px;
            animation: loading-data2 0.6s infinite;
        }

        .loading-data div:nth-child(3) {
            left: 32px;
            animation: loading-data2 0.6s infinite;
        }

        .loading-data div:nth-child(4) {
            left: 56px;
            animation: loading-data3 0.6s infinite;
        }

        @keyframes loading-data1 {
            0% {
                transform: scale(0);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes loading-data3 {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(0);
            }
        }

        @keyframes loading-data2 {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(24px, 0);
            }
        }

        .data-dashboard[data-item] {
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="form-group d-flex">
                <input type="text" class="js-flatpickr form-control bg-white" style="font-size: 18px"
                    id="date-ranger-dashboard" name="date-ranger-dashboard" placeholder="Chọn khoản thời gian"
                    data-mode="range" data-max-date="today" data-locale="vn" data-date-format="d-m-Y">
                <button class="btn btn-primary btn-export-dashboard ml-1" style="width: 150px">Export</button>
            </div>
        </div>
    </div>
    <div class="row area-dashboard-data">
        <div class="col-md-4">
            <div class="block block-bordered block-rounded">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-15">
                            <i class="si si-user fa-3x text-success"></i>
                        </div>
                        <div class="loading-data">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="font-size-h4 font-w600 data-dashboard" data-item="total_user">
                            {{ number_format($dashboard->total_user) }}</div>
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
                        <div class="loading-data">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="font-size-h4 font-w600 data-dashboard" data-item="total_order">
                            {{ number_format($dashboard->total_order) }}</div>
                        <div class="font-size-h5 font-w600">đơn hàng</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-bordered block-rounded">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-15">
                            <i class="si si-wallet fa-3x text-info"></i>
                        </div>
                        <div class="loading-data">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="font-size-h4 font-w600 data-dashboard" data-item="total_bonus">
                            {{ number_format($dashboard->total_bonus) }}</div>
                        <div class="font-size-h5 font-w600">hoa hồng</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-bordered block-rounded">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-15">
                            <i class="si si-wallet fa-3x text-info"></i>
                        </div>
                        <div class="loading-data">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="font-size-h4 font-w600 data-dashboard" data-item="total_sale">
                            {{ number_format($dashboard->total_sale) }}</div>
                        <div class="font-size-h5 font-w600">doanh số</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-bordered block-rounded">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-15">
                            <i class="si si-wallet fa-3x text-info"></i>
                        </div>
                        <div class="loading-data">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="font-size-h4 font-w600 data-dashboard" data-item="total_withdraw">
                            {{ number_format($dashboard->total_withdraw) }}</div>
                        <div class="font-size-h5 font-w600">tiền rút</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/flatpickr/l10n/vn.js?i=1') }}"></script>
    <script>
        window.startDateDefault = '{{ \Carbon\Carbon::now()->subMonth()->format('Y-m-d') }}';
        window.endDateDefault = '{{ \Carbon\Carbon::now()->format('Y-m-d') }}';
        window.startDate = '{{ \Carbon\Carbon::now()->subMonth()->format('d-m-Y') }}';
        window.endDate = '{{ \Carbon\Carbon::now()->format('d-m-Y') }}';
        window.dateChanged = false;
    </script>
    <script>
        const getDate = (date) => {
            let day = date.getDate();
            let month = date.getMonth() + 1;
            let year = date.getFullYear();
            if (day < 10) day = '0' + day;
            if (month < 10) month = '0' + month;
            return `${year}-${month}-${day}`;
        }
        const loadDashboarData = (start_date, end_date) => {
            $('.area-dashboard-data .loading-data').fadeIn(200);
            $('.area-dashboard-data .data-dashboard').fadeOut(200);
            $.post('/get-dashboard', {
                start_date,
                end_date,
                format: true
            }).then(result => {
                const dashboard = result.dashboard;
                for (const item of Object.keys(dashboard)) {
                    $(`.area-dashboard-data .data-dashboard[data-item="${item}"]`).text(dashboard[item]);
                }
            }).catch(err => {
                const error = err.responseJSON;
                alert(error.message);
            }).done(() => {
                setTimeout(() => {
                    $('.area-dashboard-data .loading-data').fadeOut(200);
                    $('.area-dashboard-data .data-dashboard').fadeIn(200);
                }, 200)
            });
        }
        $('.js-flatpickr:not(.js-flatpickr-enabled)').each((index, element) => {
            let el = $(element);
            el.addClass('js-flatpickr-enabled');
            flatpickr(el, {
                defaultDate: [window.startDate, window.endDate],
                onChange: (selectedDates) => {
                    if (selectedDates.length != 2) return;
                    const start_date = getDate(selectedDates[0]);
                    const end_date = getDate(selectedDates[1]);
                    window.startDate = start_date;
                    window.endDate = end_date;
                    window.dateChanged = true;
                    loadDashboarData(start_date, end_date);
                }
            });
        });
        $(() => {
            setTimeout(() => {
                $('.area-dashboard-data .loading-data').fadeOut(200);
                $('.area-dashboard-data .data-dashboard').fadeIn(200);
            }, 200);
            $('.btn-export-dashboard').on('click', () => {
                const start = window.dateChanged ? window.startDate : window.startDateDefault;
                const end = window.dateChanged ? window.endDate : window.endDateDefault;
                const url = `/dashboard/export?start_date=${start}&end_date=${end}`;
                window.location.href = url;
            });
        });
    </script>
@endsection
