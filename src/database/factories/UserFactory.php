<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        $faker = Faker::create('ja_JP');

        $images = [
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669463380627456/default_avatar.png?ex=67857788&is=67842608&hm=15fee9f54db62b17de009650414c5d8b3fa2b5e965cb2d29cb32bebdb69d2aef&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669463657316415/default_avatar1.png?ex=67857788&is=67842608&hm=54c99b044aabce5eaac9eaf198ab5180751ca3113e703dc6c14841baf8ce14bf&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669464097853461/default_avatar2.png?ex=67857789&is=67842609&hm=c883cf0952183d733cb1b2dd427cdbdc49f9c83dafeddd8b5ef9bb23a348dde7&',
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

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
