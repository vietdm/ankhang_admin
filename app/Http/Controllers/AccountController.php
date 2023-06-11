<?php

namespace App\Http\Controllers;

use App\Models\AdminAccount;
use App\Models\AdminRole;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function accounts()
    {
        $accounts = AdminAccount::all();
        $listRole = AdminRole::all();

        $roles = [];
        foreach ($listRole as $role) {
            $roles[$role->code] = $role->name;
        }

        return view('accounts.index', compact('accounts', 'roles'));
    }

    public function createAccount(Request $request)
    {
        $username = $request->username;
        $fullname = $request->fullname;
    }
}
