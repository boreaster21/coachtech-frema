<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Role;

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
        $userRole = Role::firstOrCreate(['name' => 'user']);
        User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => $userRole->id,
        ]);
        User::factory(10)->create();
        Product::factory(30)->create();
        Comment::factory(50)->create();
    }
}

