import {Alert} from "./alert.js";

export const Order = {
    accept(id, el) {
        Alert.confirm('Chắc chắn KÍCH HOẠT đơn hàng này?').then(() => {
            $.post(`/order/${id}/accept`).then(result => {
                Alert.success(result.message);
                const tdStatus = $(el).closest('tr').find('.td-status-badge');
                $(el).closest('td').find('.btn-created').remove();
                tdStatus.find('.badge').remove();
                tdStatus.append('<span class="badge badge-success">Đã xác nhận</span>');
            }).catch(error => {
                Alert.error(error.responseJSON.message);
            });
        });
    },
    cancel(id, el) {
        Alert.confirm('Chắc chắn HỦY đơn hàng này?').then(() => {
            $.post(`/order/${id}/cancel`).then(result => {
                Alert.success(result.message);
                const tdStatus = $(el).closest('tr').find('.td-status-badge');
                $(el).closest('td').find('.btn-created').remove();
                tdStatus.find('.badge').remove();
                tdStatus.append('<span class="badge badge-danger">Đã hủy</span>');
            }).catch(error => {
                Alert.error(error.responseJSON.message);
            });
        });
    },
    payed(id, el) {
        Alert.confirm('Chắc chắn XÁC NHẬN THANH TOÁN cho đơn hàng này?').then(() => {
            $.post(`/order/${id}/payed`).then(result => {
                Alert.success(result.message);
                const tdStatus = $(el).closest('tr').find('.td-status-pay');
                tdStatus.find('.badge').remove();
                tdStatus.find('.area-status-pay').append('<span class="badge badge-success">Đã thanh toán</span>');
                $(el).remove();
            }).catch(error => {
                Alert.error(error.responseJSON.message);
            });
        });
    }
}

window.Order = Order;
