import { Alert } from "./alert.js";

export const Common = {
    timeDelay: 0,
    minTime(time) {
        this.timeDelay = time;
        return this;
    },
    post(url, data = {}) {
        const startTime = new Date().getTime();
        return new Promise((resolve, reject) => {
            $.post(url, data).then((result) => {
                const endTime = new Date().getTime();
                const elapsedTime = endTime - startTime;
                const remainingDelay = this.timeDelay - elapsedTime;
                this.timeDelay = 0;
                if (remainingDelay <= 0) return resolve(result);
                setTimeout(() => resolve(result), remainingDelay);
            }).catch((error) => {
                if (error.status === 401) {
                    Alert.error('Phiên đăng nhập đã hết hạn. Cần đăng nhập lại!');
                    setTimeout(() => {
                        window.location.href = '/auth0/login?next=' + encodeURIComponent(window.location.href)
                    }, 1000);
                } else {
                    return reject(error.responseJSON);
                }
            });
        });
    }
}

window.Common = Common;
