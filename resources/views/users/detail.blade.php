@extends('layout')
@section('content')
    <div class="pb-100">
        <div class="block">
            <div class="block-header alert-primary">
                <h3 class="block-title font-weight-bold">Thông tin chi tiết</h3>
            </div>
            <div class="block-content pb-3">
                <ul>
                    <li><b>Username: </b>{{ $user->username }}</li>
                    <li><b>CCCD: </b>{{ $user->cccd }}</li>
                    <li><b>Email: </b>{{ $user->email }}</li>
                    <li><b>SĐT: </b>{{ $user->phone }}</li>
                    <li><b>Họ tên: </b>{{ $user->fullname }}</li>
                    <li>
                        <b>Người giới thiệu: </b>
                        {{ $user->_parent->fullname ?? 'Không rõ' }}
                        @if ($user->_parent)
                            ({{ $user->_parent->username }})
                        @endif
                    </li>
                    <li>
                        <b>Trạng thái: </b>
                        @if ($user->verified === 1)
                            <span class="text-success">Đã xác thực email</span>
                        @endif
                        @if ($user->verified === 0)
                            <span class="text-danger">Chưa xác thực email</span>
                        @endif
                    </li>
                    <li><b>Địa chỉ: </b>{{ $user->address }}</li>
                    <li><b>Cấp bậc: </b>{{ $user->levelText() }}</li>
                    <li><b>Gói tham gia: </b>{{ $user->packageText() }}</li>
                    <li><b>Tổng số tiền đã mua hàng: </b>{{ number_format($user->total_buy) }}đ</li>
                    <li style="list-style: none">========================</li>
                    <li><b>Điểm thưởng xem video: </b>{{ number_format($user->user_money->reward_point) }}</li>
                    <li><b>Tiền BONUS: </b>{{ number_format($user->user_money->money_bonus) }}</li>
                    <li><b>Điểm AKG: </b>{{ number_format($user->user_money->akg_point) }}</li>
                    <li><b>Điểm CASHBACK: </b>{{ number_format($user->user_money->cashback_point) }}</li>
                    <li><b>Điểm mua hàng: </b>{{ number_format($user->user_money->product_point) }}</li>
                    <li style="list-style: none">========================</li>
                    <li><b>Tổng thành viên trong nhóm: </b>{{ number_format($totalChild) }}</li>
                    <li><b>Tổng doanh số: </b>{{ number_format($totalSale) }}</li>
                    <li><b>Tổng đơn hàng: </b>{{ number_format($totalOrder) }}</li>
                </ul>
            </div>
        </div>
        <div class="block">
            <div class="block-header alert-primary">
                <h3 class="block-title font-weight-bold">Thông tin cây cha của người dùng</h3>
            </div>
            <div class="block-content pb-3">
                <table id="table-order" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center no-sort">#</th>
                            <th class="text-center no-sort">Username</th>
                            <th class="text-center no-sort">Họ Tên</th>
                            <th class="text-center no-sort" style="width: 150px">Chức vụ</th>
                            <th class="text-center no-sort"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parents as $parent)
                            <tr>
                                <td class="text-center" style="min-width: 100px">{{ $parent->id }}</td>
                                <td class="text-center">{{ $parent->username }}</td>
                                <td class="text-center" style="min-width: 120px"
                                    data-search="{{ convert_vi_to_en($parent->fullname) . ' ' . $parent->fullname }}">
                                    {{ $parent->fullname }}
                                </td>
                                <td class="text-center td-status-badge" style="width: 150px">{!! $parent->levelText() !!}</td>
                                <td class="text-center">
                                    <a href="/user/{{ $parent->id }}" class="text-primary">Chi tiết</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="block">
            <div class="block-header alert-primary block-header-view-child">
                <h3 class="block-title font-weight-bold">Thông tin cây con của người dùng</h3>
            </div>
            <div class="block-content block-content-view-child pb-3" style="min-height: 750px"></div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/common.js') }}" type="module"></script>
    <script>
        let statusReadyScrollToChild = false;
        const reloadDatatable = () => {
            jQuery(".js-dataTable-full").dataTable({
                columnDefs: [{
                    orderable: false,
                    targets: 'no-sort'
                }],
                pageLength: 15,
                lengthMenu: [
                    [5, 15, 30, 50],
                    [5, 15, 30, 50]
                ],
                autoWidth: false,
                responsive: true,
                aaSorting: [],
                language: {
                    emptyTable: "Không có người dùng nào"
                }
            });
        }
        let datas = {
            ids: [],
            data: {}
        };
        const getChildData = (userId = null) => {
            if (userId == null) {
                if (datas.ids.length <= 1) {
                    Common.alert.error('Đã tới người dùng cuối cùng.');
                    return;
                }
                datas.ids.pop();
                userId = datas.ids[datas.ids.length - 1];
            } else {
                datas.ids.push(userId);
            }
            if (typeof datas.data[userId] != 'undefined') {
                const html = datas.data[userId];
                $('.block-content-view-child').empty().append(html);
                reloadDatatable();
                scrollToListChild();
                return;
            }
            Common.post(`/user/${userId}/children`).then((result) => {
                let html = `
                    <div class="mb-3">
                        <p class="mb-0">Đang xem toàn bộ F1 của <b>${result.user.fullname} (${result.user.username})</b></p>
                        ${datas.ids.length <= 1 ? '' : '<div><span class="text-link text-info view-up-line"><i class="fa fa-backward mr-2"></i>Quay lại</span></div>'}
                    </div>
                `;
                html += result.html;
                datas.data[userId] = html;
                $('.block-content-view-child').empty().append(html);
                reloadDatatable();
                scrollToListChild();
            }).catch((error) => {
                Common.alert.error(error.message);
            });
        }
        const scrollToListChild = () => {
            if (!statusReadyScrollToChild) {
                statusReadyScrollToChild = true;
                return;
            }
            $([document.documentElement, document.body]).animate({
                scrollTop: $('.block-header-view-child').offset().top - 80
            }, 300);
        }
        $(() => {
            getChildData('{{ $user->id }}');
            $('.block-content-view-child').on('click', '.view-down-line', function() {
                const id = $(this).attr('data-id');
                getChildData(id);
            });
            $('.block-content-view-child').on('click', '.view-up-line', function() {
                getChildData();
            });
        });
    </script>
@endsection
