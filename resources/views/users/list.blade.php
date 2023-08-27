@extends('layout')
@section('content')
    {!! view('users.table-data', ['users' => $users, 'is_total' => true])->render() !!}

    <div class="modal fade" id="modalChangePassword" tabindex="-1" role="dialog" aria-labelledby="modalChangePassword"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Đổi mật khẩu cho <span class="txt_username"></span> (<span
                                class="txt_fullname"></span>)</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content text-center">
                        <form action="">
                            <input type="hidden" name="user_id" value="">
                            <div class="form-group">
                                <label for="password" class="d-block text-left">Mật khẩu mới</label>
                                <input type="text" class="form-control" id="password" name="password">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-alt-success btn-accept-change-password">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/common.js') }}" type="module"></script>
    <script>
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
            autoWidth: !1,
            responsive: true
        });
        $('.textlink-change-password').on('click', function(e) {
            e.preventDefault();
            const id = $(this).attr('data-id');
            const username = $(this).attr('data-username');
            const fullname = $(this).attr('data-fullname');

            const $modal = $("#modalChangePassword");

            $modal.find('form').trigger('reset');
            $modal.find('.txt_username').text(username);
            $modal.find('.txt_fullname').text(fullname);
            $modal.find('[name="user_id"]').val(id);
            $modal.modal('show');
        });
        $('.btn-accept-change-password').on('click', function() {
            const $this = $(this);
            const $modal = $("#modalChangePassword");
            const $form = $modal.find('form');
            const user_id = $form.find('[name="user_id"]').val();
            const password = $form.find('[name="password"]').val().trim();

            $this.prop('disabled', true);

            Common.post('/user/change_password', {
                user_id,
                password
            }).then((result) => {
                Common.alert.success(result.message);
                $modal.modal('hide');
                $this.prop('disabled', false);
            }).catch(error => {
                Common.alert.error(error.message);
                $this.prop('disabled', false);
            });
        });
    </script>
@endsection
