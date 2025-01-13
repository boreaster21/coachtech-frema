<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ConditionSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('conditions')->insert([
            'name' => '新品、未使用',
        ]);

        DB::table('conditions')->insert([
            'name' => '未使用に近い',
        ]);

        DB::table('conditions')->insert([
            'name' => '目立った傷や汚れなし',
        ]);

        DB::table('conditions')->insert([
            'name' => 'やや傷や汚れあり',
        ]);

        DB::table('conditions')->insert([
            'name' => '傷や汚れあり',
        ]);
    }
}
