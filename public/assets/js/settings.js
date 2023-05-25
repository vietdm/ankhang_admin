import { Alert } from './alert.js';

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
        $('.btn-update-role').on('click', function() {
            console.log($('.input-select-role:checked'));
        });
    }
}

Settings.init();

window.Settings = Settings;
