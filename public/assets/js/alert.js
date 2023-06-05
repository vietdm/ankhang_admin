export const Alert = {
    success(mgs) {
        Swal.fire({
            title: 'Success!',
            text: mgs,
            icon: 'success'
        });
    },
    error(mgs) {
        Swal.fire({
            title: 'Error!',
            text: mgs,
            icon: 'error'
        });
    },
    confirm(mgs) {
        return new Promise((resolve, reject) => {
            Swal.fire({
                title: mgs,
                showCancelButton: false,
                showDenyButton: true,
                confirmButtonText: 'Xác nhận',
                denyButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    resolve(true);
                } else if (result.isDenied) {
                    reject(false);
                }
            })
        });
    }
}

window.AlertCommon = Alert;
