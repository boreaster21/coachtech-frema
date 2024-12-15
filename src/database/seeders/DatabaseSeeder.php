<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            ConditionSeeder::class,
            PaymentSeeder::class,
        ]);

        User::factory()->admin()->create();
        User::factory(10)->create();
        Product::factory(30)->create();
        Comment::factory(50)->create();
    }
}

