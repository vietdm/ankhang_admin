@extends('event.layout')
@section('content.event')
    <table id="table-event" class="table table-bordered table-striped table-vcenter js-dataTable-full">
        <thead>
            <tr>
                <th class="text-center no-sort">#</th>
                <th class="text-center">Username</th>
                <th class="text-center">Họ Tên</th>
                <th class="text-center no-sort">Email</th>
                <th class="text-center no-sort">Số ĐT</th>
                <th class="text-center no-sort">Phần thưởng</th>
                <th class="text-center no-sort">Trạng thái</th>
                <th class="text-center no-sort" style="width: 120px">Action</th>
            </tr>
        </thead>
        <tbody>
            @php $index = 1 @endphp
            @php $giftName = config('event.lucky_name') @endphp
            @foreach ($events as $event)
                @php $user = $event->user @endphp
                @php $gift = $giftName[$event->gift] ?? 'Không rõ' @endphp
                <tr>
                    <td class="text-center" style="min-width: 100px">{{ $index++ }}</td>
                    <td class="text-center">{{ $user->username }}</td>
                    <td class="text-center" style="min-width: 120px"
                        data-search="{{ convert_vi_to_en($user->fullname) . ' ' . $user->fullname }}">
                        {{ $user->fullname }}
                    </td>
                    <td class="text-center">{{ $user->email }}</td>
                    <td class="text-center">{{ $user->phone }}</td>
                    <td class="text-center">{{ $gift }}</td>
                    <td class="text-center td-status">
                        @if ($event->is_given === 1 || $event->gift == "MM")
                            <span class="text-success">Đã hoàn thành</span>
                        @else
                            <span class="text-warning">Chưa hoàn thành</span>
                        @endif
                    </td>
                    <td class="text-center td-action">
                        @if ($event->is_given === 0 && $event->gift != "MM")
                            <div class="d-block text-center ">
                                <div data-id="{{ $event->id }}" class="text-primary text-link"
                                    onClick="confirmSuccessGiftOfEvent(this)">Xác nhận đã hoàn thành</div>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@section('script.event')
    <script src="{{ asset('assets/js/common.js') }}" type="module"></script>
    <script>
        const confirmSuccessGiftOfEvent = (el) => {
            const event_id = $(el).attr('data-id');
            Common.alert.confirm('Chắc chắn đã hoàn thành phần quà này?').then(() => {
                Common.post('/lucky-event/update', {
                    event_id
                }).then(() => {
                    const $tr = $(el).closest('tr');
                    $tr.find('.td-status').empty().append('<span class="text-success">Đã hoàn thành</span>');
                    $tr.find('.td-action').empty();
                }).catch(error => {
                    Common.alert.error(error.message);
                });
            });
        }
    </script>
@endsection
