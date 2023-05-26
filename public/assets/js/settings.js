import { Alert } from './alert.js';
import { Common } from './common.js';

export const Settings = {
    init() {
        this.triggerEventChangeInputRole();
        this.triggerEventChangeUserChangeRole();
        this.triggerClickUpdateRole();
    },
    triggerEventChangeInputRole() {
        $('.input-select-role').on('change', function () {
            if ($(this).val() == 'all') {
                if ($(this).prop('checked') === true) {
                    $('.input-select-role').prop('checked', false);
                    $(this).prop('checked', true);
                }
            } else {
                $('.input-select-role[value="all"]').prop('checked', false);
            }
        });
    },
    triggerEventChangeUserChangeRole() {
        $('#user_id_change_role').on('change', function () {
            const userIdSelect = $(this).val();

            $('.input-select-role').prop('checked', false);

            if (userIdSelect == '') {
                $('.input-select-role').prop('disabled', true);
                return;
            }

            $('.input-select-role').prop('disabled', false);

            const userRole = UserRoleList[userIdSelect];

            for (const role of userRole) {
                $(`.input-select-role[value="${role}"]`).prop('checked', true);
            }
        });
    },
    triggerClickUpdateRole() {
        $('.btn-update-role').on('click', async function () {
            const elRoleSelected = $('.input-select-role:checked');
            if (elRoleSelected.length == 0) {
                Alert.error('Cần chọn ít nhất 1 quyền mới có thể cập nhật!');
                return;
            }

            const self = this;

            $(self).prop('disabled', true);

            const roles = [];
            for (const el of elRoleSelected) {
                roles.push(el.value);
            }

            const userIdUpdate = $('#user_id_change_role').val();
            if (userIdUpdate == '') {
                Alert.error('Cần chọn tài khoản để cập nhật!');
                return;
            }

            const isSelfUpdate = userIdUpdate == UserLogin.id;
            if (isSelfUpdate) {
                if (!(await Alert.confirm('Bạn đang thay đổi quyền của chính mình. Sau khi cập nhật có thể sẽ không truy cập được 1 số chức năng. Vẫn cập nhật chứ?'))) {
                    $(self).prop('disabled', false);
                    return;
                }
            }

            Common.post('/setting/update/role', { roles, user_id: userIdUpdate })
                .then((result) => {
                    Alert.success(result.message);
                    $(self).prop('disabled', false);
                    UserRoleList[userIdUpdate] = roles;
                    if(isSelfUpdate) {
                        setTimeout(() => window.location.reload(), 1000);
                    }
                }).catch((error) => {
                    Alert.error(error.message);
                    $(self).prop('disabled', false);
                });
        });
    }
}

Settings.init();

window.Settings = Settings;
