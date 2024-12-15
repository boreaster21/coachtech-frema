<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'user_id' => '新品、未使用',
        ]);
        DB::table('products')->insert([
            'product_photo_path' => 'profile-icons/A7gCZJqjTIjzzWVdYIN3K8bhXLPQoamgqtRQpf3k.jpg',
        ]);
        DB::table('products')->insert([
            'name' => 'やわらか男子',
        ]);
        DB::table('products')->insert([
            'description' => 'とても柔らかい雰囲気のメンズイラストです',
        ]);
        DB::table('products')->insert([
            'price' => '￥100,000',
        ]);
        DB::table('products')->insert([
            'condition_id' => '1',
        ]);
        DB::table('products')->insert([
            'category_id_1' => '1',
        ]);
        DB::table('products')->insert([
            'category_id_2' => '2',
        ]);
    }
}
