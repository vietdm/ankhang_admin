@extends('layout')
@section('head')
    <style>
        #add_edit_account_admin .add,
        #add_edit_account_admin.edit .edit {
            display: block;
        }

        #add_edit_account_admin .edit,
        #add_edit_account_admin.edit .add {
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="block">
        <div class="block-header alert-primary">
            <h3 class="block-title font-weight-bold">Quản lý tài khoản ADMIN</h3>
            {{-- <button class="btn btn-success btn-create-account">Tạo mới</button> --}}
        </div>
        <div class="block-content pb-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Họ tên</th>
                        <th class="text-center">Quyền</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <td class="text-center">{{ $account->id }}</td>
                            <td class="text-center">{{ $account->username }}</td>
                            <td class="text-center">{{ $account->fullname }}</td>
                            <td>
                                <ul>
                                    @foreach ($account->role as $role)
                                        <li>{{ $roles[$role] }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-center">
                                {{-- <button class="btn btn-info">Sửa</button>
                                <button class="btn btn-danger">Xóa</button> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade add" id="add_edit_account_admin" tabindex="-1" role="dialog"
        aria-labelledby="add_edit_account_admin" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title add">Thêm tài khoản mới</h3>
                        <h3 class="block-title edit">Sửa thông tin tài khoản</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <form action="">
                            <div class="form-group">
                                <label for="">Username</label>
                                <input type="text" name="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Họ và tên (Bí danh)</label>
                                <input type="text" name="fullname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Mật khẩu</label>
                                <input type="text" name="password" class="form-control">
                                <i class="edit">* Để trống nếu không thay đổi mật khẩu</i>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary add btn-create-account">Thêm</button>
                    <button type="button" class="btn btn-info edit">Sửa</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/common.js?i=1') }}" type="module"></script>
    <script>
        const getFormAccountData = () => {
            const $modal = $('#add_edit_account_admin');
            const username = $modal.find('[name="username"]').val().trim();
            if (username == '') {
                Common.alert.error('Hãy nhập username');
                return null;
            }
            const fullname = $modal.find('[name="fullname"]').val().trim();
            if (fullname == '') {
                Common.alert.error('Hãy nhập họ tên hoặc bí danh');
                return null;
            }
            const password = $modal.find('[name="password"]').val().trim();
            if (password == '' && $modal.hasClass('add')) {
                Common.alert.error('Hãy nhập mật khẩu');
                return null;
            }
            return {
                username,
                fullname,
                password
            };
        }
        $('.btn-create-account').on('click', function() {
            const $modal = $('#add_edit_account_admin');
            $modal.removeClass('edit').addClass('add');
            $modal.find('form').trigger('reset');
            $modal.modal();
        });
        $('.btn-create-account').on('click', function() {
            const $modal = $('#add_edit_account_admin');
            const formData = getFormAccountData();
            if (formData === null) return;
        });
    </script>
@endsection
