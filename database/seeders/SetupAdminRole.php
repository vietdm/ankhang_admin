<?php

namespace Database\Seeders;

use App\Models\AdminRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetupAdminRole extends Seeder
{
    protected $roles = [
        'all'               => 'Có tất cả các quyền',
        'all_order'         => 'Xem toàn bộ danh sách đặt hàng',
        'confirm_order'     => 'Xác nhận đặt hàng',
        'transfer_order'    => 'Vận chuyển đơn hàng',
        'confirm_withdraw'  => 'Xác nhận rút tiền',
        'settings'          => 'Cài đặt hệ thống',
        'akg'               => 'Xem thống kê và cập nhật điểm AKG',
        'create_order'      => 'Tạo đơn hàng thủ công',
        'view_user'         => 'Xem thông tin user',
    ];
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE admin_role');
        DB::statement('ALTER table admin_role AUTO_INCREMENT = 1');

        foreach ($this->roles as $code => $name) {
            AdminRole::insert([
                'code' => $code,
                'name' => $name
            ]);
        }
    }
}
