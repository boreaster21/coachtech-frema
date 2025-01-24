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
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669463380627456/default_avatar.png?ex=67934f48&is=6791fdc8&hm=5cf3db1127fa31fef3557e529eba03cec227a4ac511899c0a2ea9f16a184be22&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669463657316415/default_avatar1.png?ex=67934f48&is=6791fdc8&hm=6bd70df70db32a19499a7e645eb563b3744e30a6425649a291cd15789f5bc1d1&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669464097853461/default_avatar2.png?ex=67934f49&is=6791fdc9&hm=81705d2477c24b0ba5f7e3e03dd379b17c9b9217b470e0de2aeb8a62dff52d26&',
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
