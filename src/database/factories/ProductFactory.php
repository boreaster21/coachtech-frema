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
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669464710217728/smpl.png?ex=67857789&is=67842609&hm=b610866060e39839e57f0148f56381b54126e2b3616c72dd3d553516c4fc23f0&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669465242763264/smpl1.png?ex=67857789&is=67842609&hm=8d64fdce54a5648b87e21f6727174250d6de76c5aafde31ba127f1d7db281f7c&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669465599410186/ampl2.png?ex=67857789&is=67842609&hm=dac9d150e935b4c726a1312c9dcbceb12009ddf655df43a8bd0a17c860d29737&',
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
