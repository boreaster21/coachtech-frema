<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->updateOrInsert(
            ['name' => 'admin'], // 一意条件
            ['created_at' => now(), 'updated_at' => now()] // 更新内容
        );

        DB::table('roles')->updateOrInsert(
            ['name' => 'user'],
            ['created_at' => now(), 'updated_at' => now()]
        );
    }
}
