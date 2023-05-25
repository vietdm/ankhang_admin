<?php

namespace App\Http\Controllers;

use App\Models\AdminAccount;
use App\Models\AdminRole;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function home() {
        $users = AdminAccount::select(['id', 'fullname', 'role'])->get();
        $roles = AdminRole::all();
        return view('settings.index', compact('users', 'roles'));
    }
}
