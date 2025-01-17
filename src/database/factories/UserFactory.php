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
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669463380627456/default_avatar.png?ex=678b6648&is=678a14c8&hm=eb73b26e1e9370debc9a296ec4f23ad1e154345fcc3fa63c2ee808a8857ae844&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669463657316415/default_avatar1.png?ex=678b6648&is=678a14c8&hm=229385df691c82678eb1d9bd4f3682e1e24bbdc1aebb8e87ac9203c0992ba935&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669464097853461/default_avatar2.png?ex=678b6649&is=678a14c9&hm=5fdb73fbaba028bfcc64cf430bff8067458f8843f62734c8bffa9fd0bdc9a139&',
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
