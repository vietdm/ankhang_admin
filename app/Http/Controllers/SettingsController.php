<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\AdminAccount;
use App\Models\AdminRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

class SettingsController extends Controller
{
    public function home()
    {
        $users = AdminAccount::select(['id', 'fullname', 'role'])->get();
        $roles = AdminRole::all();
        return view('settings.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request)
    {
        $roles = $request->roles ?? null;
        $userId = $request->user_id ?? null;

        if (gettype($roles) != 'array') {
            return Response::badRequest('Không tìm thấy quyền để cập nhật!');
        }

        if (empty($userId)) {
            return Response::badRequest('Không tìm thấy tài khoản cần cập nhật!');
        }

        $adminAccount = AdminAccount::whereId($userId)->first();
        if ($adminAccount == null) {
            return Response::badRequest('Không tìm thấy tài khoản cần cập nhật!');
        }

        DB::beginTransaction();
        try {
            $adminAccount->role = $roles;
            $adminAccount->save();
            DB::commit();
            return Response::success('Cập nhật thành công!');
        } catch (Exception | PDOException $e) {
            logger($e);
            DB::rollBack();
            return Response::badRequest('Có lỗi khi cập nhật quyền. Vui lòng liên hệ quản trị viên!');
        }
    }
}
