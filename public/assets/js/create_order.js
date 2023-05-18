import {Alert} from "./alert.js";

export const CreateOrder = {
    create(form) {
        Alert.confirm('Xác nhận TẠO ĐƠN HÀNG này?').then(() => {
            $.post(`/withdraw/${id}/accept`).then(result => {
                Alert.success(result.message);
                const tdStatus = $(el).closest('tr').find('.td-status');
                $(el).closest('td').find('.btn').remove();
                tdStatus.find('.badge').remove();
                tdStatus.append('<span class="badge badge-primary">Đã xác nhận</span>');
            }).catch(error => {
                Alert.error(error.responseJSON.message);
            });
        });
        return false;
    },
}

window.CreateOrder = CreateOrder;
