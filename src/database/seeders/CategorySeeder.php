<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            'name' => 'ファッション',
        ]);
        DB::table('categories')->insert([
            'name' => '食品',
        ]);
        DB::table('categories')->insert([
            'name' => 'アウトドア、釣り、旅行用品',
        ]);
        DB::table('categories')->insert([
            'name' => 'ダイエット、健康',
        ]);
        DB::table('categories')->insert([
            'name' => 'コスメ、美容、ヘアケア',
        ]);
        DB::table('categories')->insert([
            'name' => 'スマホ、タブレット、パソコン',
        ]);
        DB::table('categories')->insert([
            'name' => 'テレビ、オーディオ、カメラ',
        ]);
        DB::table('categories')->insert([
            'name' => '家電',
        ]);
        DB::table('categories')->insert([
            'name' => '家具、インテリア',
        ]);
        DB::table('categories')->insert([
            'name' => '花、ガーデニング',
        ]);
        DB::table('categories')->insert([
            'name' => 'キッチン、日用品、文具',
        ]);
        DB::table('categories')->insert([
            'name' => 'DIY、工具',
        ]);
        DB::table('categories')->insert([
            'name' => 'ペット用品、生き物',
        ]);
        DB::table('categories')->insert([
            'name' => '楽器、手芸、コレクション',
        ]);
        DB::table('categories')->insert([
            'name' => 'ゲーム、おもちゃ',
        ]);
        DB::table('categories')->insert([
            'name' => 'ベビー、キッズ、マタニティ',
        ]);
        DB::table('categories')->insert([
            'name' => 'スポーツ',
        ]);
        DB::table('categories')->insert([
            'name' => '車、バイク、自転車',
        ]);
        DB::table('categories')->insert([
            'name' => 'CD、レコード、音楽ソフト、チケット',
        ]);
        DB::table('categories')->insert([
            'name' => 'DVD、映像ソフト',
        ]);
        DB::table('categories')->insert([
            'name' => '本、雑誌、コミック',
        ]);
    }
}
