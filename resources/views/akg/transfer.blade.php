@extends('layout')
@section('content')
    <div class="block">
        <div class="block-header alert-primary">
            <h3 class="block-title font-weight-bold">Chuyển điểm AKG</h3>
        </div>
        <div class="block-content pb-3">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="point">Số điểm</label>
                <input type="number" class="form-control" id="point" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="point" class="d-block">Nội dung chuyển (chọn nội dung có sẵn hoặc điền nội dung mới)</label>
                <i style="font-size:14px">Chọn nội dung có sẵn</i>
                <select id="content_select" class="form-control mb-1">
                    <option value="">Chọn nội dung</option>
                    @foreach ($types as $type)
                        <option value="{{ $type['type'] }}">{{ $type['content'] }}</option>
                    @endforeach
                </select>
                <i style="font-size:14px">Hoăc nhập nội dung mới</i>
                <input type="text" class="form-control" id="content_new" autocomplete="off">
            </div>
            <button class="btn btn-primary btn-transfer">Chuyển</button>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/alert.js?i=1') }}" type="module"></script>
    <script>
        $('.btn-transfer').on('click', function() {
            const username = $('#username').val().trim();
            if (username == '') {
                return AlertCommon.error('Hãy nhập Username');
            }

            const point = $('#point').val().trim();
            if (point == '') {
                return AlertCommon.error('Hãy nhập Số điểm');
            }
            if (parseInt(point) <= 0) {
                return AlertCommon.error('Số điểm không hợp lệ');
            }

            const content_select = $('#content_select').val();
            const content_new = $('#content_new').val().trim();
            if (content_select == '' && content_new == '') {
                return AlertCommon.error('Hãy chọn một nội dung chuyển điểm hoặc nhập nội dung mới');
            }


            AlertCommon.success('dsd');
        });
    </script>
@endsection
