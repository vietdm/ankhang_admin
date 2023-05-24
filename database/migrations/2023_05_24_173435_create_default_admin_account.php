<?php

use App\Models\AdminAccount;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        AdminAccount::insert([
            'username' => 'admin',
            'password' => bcrypt('admin1'),
            'fullname' => 'Administator',
            'role' => [
                'all'
            ]
        ]);
    }
};
