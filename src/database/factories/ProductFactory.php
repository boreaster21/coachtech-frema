<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $faker = Faker::create('ja_JP');

        $images = [
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669464710217728/smpl.png?ex=67934f49&is=6791fdc9&hm=2bd4c4244ebb2d55173a2e43d223e150c3a4b88b2e7e5af3058d8180512f2539&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669465242763264/smpl1.png?ex=67934f49&is=6791fdc9&hm=8a00b025e5758467f7a9b81903732391c189677c1bcf67c8bd914470cb0999a3&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669465599410186/ampl2.png?ex=67934f49&is=6791fdc9&hm=cead0c6a82e3cbd5683cd4efaa8cebf3943c4dcbc8172c3796d7d2ffd429b503&',
        ];

        $randomImage = $images[array_rand($images)];

        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $user->id,
            'product_photo_path' => $randomImage,
            'name' => $faker->word,
            'brand_name' => $faker->word,
            'description' => $faker->sentence,
            'price' => $this->faker->numberBetween(100, 10000),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($product) {
            $categories = Category::inRandomOrder()->take(3)->pluck('id')->toArray();
            if (empty($categories)) {
                $categories = Category::factory()->count(3)->create()->pluck('id')->toArray();
            }

            $conditions = Condition::inRandomOrder()->take(1)->pluck('id')->toArray();
            if (empty($conditions)) {
                $conditions = Condition::factory()->count(1)->create()->pluck('id')->toArray();
            }

            $product->categories()->attach($categories);
            $product->conditions()->attach($conditions);
        });
    }

}
