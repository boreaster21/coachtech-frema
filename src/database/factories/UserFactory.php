<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $faker = Faker::create('ja_JP');

        $images = [
            'https://x.gd/bHbNT',
            'https://x.gd/qvc6p',
            'https://x.gd/nwbeT',
        ];
        $randomImage = $images[array_rand($images)];

        $role = Role::where('name', 'user')->first();
        if (!$role) {
            $role = Role::firstOrCreate(['name' => 'user']); 
        }

        return [
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::$password ??= bcrypt('password'),
            'profile_photo_path' => $randomImage,
            'remember_token' => Str::random(10),
            'address' => $faker->address(),
            'postcode' => $faker->postcode(),
            'building' => $faker->secondaryAddress(),
            'role_id' => $role->id,
        ];
    }
    /**
     * Indicate that the user should have an admin role.
     */
    public function admin(): static
    {
        $role = Role::where('name', 'admin')->first();
        if (!$role) {
            $role = Role::firstOrCreate(['name' => 'admin']);
        }

        return $this->state(fn(array $attributes) => [
            'role_id' => $role->id,
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'), 
        ]);
    }



    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
