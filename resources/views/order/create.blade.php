@extends('layout')
@section('head')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
    <div class="block">
        <div class="block-header alert-primary">
            <h3 class="block-title font-weight-bold">Tạo đơn hàng thủ công</h3>
        </div>
        <div class="block-content pb-3">
            <div class="form-group">
                <label for="product_id" class="font-weight-bold">Lựa chọn sản phẩm:</label>
                <select name="product_id" id="product_id" class="form-control">
                    <option value="">===chọn một sản phẩm===</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantity" class="font-weight-bold">Nhập số lượng:</label>
                <input type="number" class="form-control" name="quantity" value="1">
            </div>
            <div class="form-group">
                <label for="user_id" class="font-weight-bold">Chọn người dùng:</label>
                <select name="user_id" id="user_id" class="form-control js-select2"
                    data-placeholder="===chọn một tài khoản===">
                    <option value=""></option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ strtolower($user->username) }}{{ !empty($user->fullname) ? ": " . $user->fullname : $user->fullname }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="fullname" class="font-weight-bold">Họ và tên:</label>
                <input type="text" class="form-control" name="fullname" readonly>
            </div>
            <div class="form-group">
                <label for="phone" class="font-weight-bold">Số điện thoại:</label>
                <input type="text" class="form-control" name="phone" readonly>
            </div>
            <div class="form-group">
                <label for="address" class="font-weight-bold">Địa chỉ:</label>
                <input type="text" class="form-control" name="address">
            </div>
            <div class="form-group">
                <label for="note" class="font-weight-bold">Ghi chú:</label>
                <textarea name="note" id="note" rows="4" class="form-control" style="resize: none"></textarea>
            </div>
            <button class="btn btn-primary btn-create-order">Tạo đơn hàng</button>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/common.js?i=1') }}" type="module"></script>
    <script>
        window.UserData = {!! json_encode($users->toArray()) !!};
        jQuery('.js-select2:not(.js-select2-enabled)').each((index, element) => {
            let el = jQuery(element);
            el.addClass('js-select2-enabled').select2({
                placeholder: el.data('placeholder') || false
            });
        });

        const getUser = (id) => {
            const index = window.UserData.findIndex((user) => user.id == id);
            if (index === -1) return null;
            return window.UserData[index];
        }

        $('#user_id').on('change', function() {
            const userId = this.value;
            let user = getUser(userId);
            if (user == null) {
                user = {
                    fullname: '',
                    phone: '',
                    address: ''
                }
            }
            if (user.fullname == '' || user.fullname == null) {
                user.fullname = '[Chưa đặt tên]';
            }
            $('[name="fullname"]').val(user.fullname);
            $('[name="phone"]').val(user.phone);
            $('[name="address"]').val(user.address);
        });

        $('.btn-create-order').on('click', function() {
            const product_id = $('[name="product_id"]').val();
            if (product_id == '') {
                Common.alert.error('Hãy chọn sản phẩm!');
                return false;
            }

            const quantity = parseInt($('[name="quantity"]').val().trim());
            if (isNaN(quantity) || quantity <= 0) {
                Common.alert.error('Hãy nhập số lượng chính xác!');
                return false;
            }

            const user_id = $('[name="user_id"]').val();
            if (user_id == '') {
                Common.alert.error('Hãy chọn người dùng!');
                return false;
            }

            const name = $('[name="fullname"]').val();
            const phone = $('[name="phone"]').val();

            const address = $('[name="address"]').val().trim();
            if (address == '') {
                Common.alert.error('Hãy nhập địa chỉ!');
                return false;
            }

            const note = 'Đơn hàng được tạo tự động: ' + $('[name="note"]').val().trim();

            const order = JSON.stringify([{
                id: product_id,
                quantity
            }]);

            Common.post('/order/create', {
                order,
                user_id,
                admin_request: '1',
                name,
                phone,
                address,
                note,
            }).then((result) => {
                Common.alert.success('Tạo đơn thành công!');
                setTimeout(() => window.location.reload(), 1000);
            }).catch((error) => {
                Common.alert.error(error.message);
            });
        });
    </script>
@endsection
