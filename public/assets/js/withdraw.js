import { Alert } from "./alert.js";

export const Withdraw = {
    accept(id, el) {
        Alert.confirm('Xác nhận ĐÃ CHUYỂN TIỀN cho yêu cầu này?').then(() => {
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
    },
    cancel(id, el) {
        Alert.confirm('Chắc chắn HỦY yêu cầu này?').then(() => {
            $.post(`/withdraw/${id}/cancel`).then(result => {
                Alert.success(result.message);
                const tdStatus = $(el).closest('tr').find('.td-status');
                $(el).closest('td').find('.btn').remove();
                tdStatus.find('.badge').remove();
                tdStatus.append('<span class="badge badge-danger">Đã hủy</span>');
            }).catch(error => {
                Alert.error(error.responseJSON.message);
            });
        });
    },
    openQr(el) {
        const bankCode = $(el).attr('data-code');
        const bankAccount = $(el).attr('data-account');
        const money = $(el).attr('data-money');
        const username = $(el).attr('data-username');
        const data = [
            `amount=${money}`,
            `addInfo=AN KHANG GROUP THANH TOAN THU NHAP`
        ];
        const url = `https://img.vietqr.io/image/${bankCode}-${bankAccount}-11sAiww.png?${data.join('&')}`;
        const $modal = $('#modalQrCodePayment');
        $modal.find('img').attr('src', url);
        $modal.modal();
    }
}

window.Withdraw = Withdraw;
