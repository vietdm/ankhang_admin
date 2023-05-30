import { Alert } from "./alert.js";
import { Common } from "./common.js";

export const Order = {
    accept(id, el) {
        Alert.confirm('Chắc chắn KÍCH HOẠT đơn hàng này?').then(() => {
            $(el).hide();
            $.post(`/order/${id}/accept`).then(() => {
                const tdStatus = $(el).closest('tr').find('.td-status-badge');
                $(el).closest('td').find('.btn-created').remove();
                tdStatus.find('.badge').remove();
                tdStatus.append('<span class="badge badge-success">Đã xác nhận</span>');
            }).catch(error => {
                $(el).show();
                Alert.error(error.responseJSON.message);
            });
        });
    },
    deliving(id, el) {
        Alert.confirm('Chắc chắn xác nhận đơn hàng ĐANG ĐƯỢC VẬN CHUYỂN?').then(() => {
            $(el).hide();
            $.post(`/order/${id}/deliving`).then(() => {
                const $tr = $(el).closest('tr');
                $tr.fadeOut(200);
                setTimeout(() => $tr.remove(), 200);
            }).catch(error => {
                $(el).show();
                Alert.error(error.responseJSON.message);
            });
        });
    },
    setDoneOrder(id, el) {
        Alert.confirm('Chắc chắn xác nhận đơn hàng ĐÃ HOÀN THÀNH?').then(() => {
            $(el).hide();
            $.post(`/order/${id}/success`).then(() => {
                const $tr = $(el).closest('tr');
                $tr.fadeOut(200);
                setTimeout(() => $tr.remove(), 200);
            }).catch(error => {
                $(el).show();
                Alert.error(error.responseJSON.message);
            });
        });
    },
    cancel(id, el) {
        Alert.confirm('Chắc chắn HỦY đơn hàng này?').then(() => {
            $.post(`/order/${id}/cancel`).then(() => {
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
            $.post(`/order/${id}/payed`).then(() => {
                const tdStatus = $(el).closest('tr').find('.td-status-pay .area-status-pay');
                tdStatus.find('.badge').remove();
                tdStatus.append('<span class="badge badge-success">Đã thanh toán</span>');
                $(el).remove();
            }).catch(error => {
                Alert.error(error.responseJSON.message);
            });
        });
    },
    loadTranferData(type) {
        const url = type == 'confirmed' ? '/order/confirmed' : '/order/deliving';
        const elListOrder = $('#list-order-transfer');
        const elLoading = elListOrder.find('.tab-pane-loading');
        const elContent = elListOrder.find('.tab-pane-content');

        elLoading.fadeIn(50);
        elContent.fadeOut(50);

        setTimeout(() => elContent.empty(), 50);

        Common.minTime(1000).post(url).then(({ html }) => {
            elLoading.fadeOut(50);
            elContent.html(html);
            elContent.find(".js-dataTable-full").dataTable({
                columnDefs: [{ orderable: false, targets: 'no-sort' }],
                pageLength: 5,
                lengthMenu: [[5, 8, 15, 20, 50], [5, 8, 15, 20, 50]],
                autoWidth: false,
                order: [[0, 'desc']],
                responsive: true,
                fixedColumns: true
            });
            elContent.fadeIn(50);
        });
    }
}

window.Order = Order;
