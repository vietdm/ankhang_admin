<table id="table-order" class="table table-bordered table-responsive table-striped table-vcenter js-dataTable-full">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Username</th>
            <th class="text-center">Họ Tên</th>
            <th class="text-center no-sort">Email</th>
            <th class="text-center no-sort">Số ĐT</th>
            <th class="text-center no-sort">CCCD</th>
            <th class="text-center" style="width: 150px">Level</th>
            <th class="text-center" style="width: 100px">Action</th>
        </tr>
    </thead>
    <tbody>
        @php $index = 1 @endphp
        @foreach ($users as $user)
            <tr>
                <td class="text-center" style="min-width: 100px">{{ $index++ }}</td>
                <td class="text-center">{{ $user->username }}</td>
                <td class="text-center" style="min-width: 120px"
                    data-search="{{ convert_vi_to_en($user->fullname) . ' ' . $user->fullname }}">
                    {{ $user->fullname }}
                </td>
                <td class="text-center">{{ $user->email }}</td>
                <td class="text-center">{{ $user->phone }}</td>
                <td class="text-center">{{ $user->cccd }}</td>
                <td class="text-center td-status-badge" style="width: 150px">{!! $user->levelText() !!}</td>
                <td class="text-center">
                    @if (($canShowLine ?? false) === true)
                        <div class="d-block text-center">
                            <span class="text-warning text-link view-down-line" data-id="{{ $user->id }}">Xem hệ
                                thống dưới</span>
                        </div>
                    @endif
                    <div class="d-block text-center ">
                        <a href="/user/{{ $user->id }}" class="text-primary">Chi tiết</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
