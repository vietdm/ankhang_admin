<?php

use App\Models\Users;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('present_username')->default(null)->after('present_phone');
        });

        $users = [];
        foreach (Users::all() as $user) {
            $parentId = $user->parent_id;
            if (empty($parentId)) continue;
            if (!isset($users[$parentId])) {
                $users[$parentId] = Users::select(['username'])->whereId($parentId)->first();
            }
            $user->present_username = $users[$parentId]->username;
            $user->save();
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('present_phone');
        });
    }
};
