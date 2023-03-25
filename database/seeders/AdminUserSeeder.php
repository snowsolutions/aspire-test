<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('admin_users')->insert([
            'id' => 1,
            'name' => 'Phuc Nguyen',
            'email' => 'admin@admin.com',
            'password' => bcrypt('654321'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
