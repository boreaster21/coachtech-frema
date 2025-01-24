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
            'https://x.gd/7CQlG',
            'https://x.gd/vC2qX',
            'https://x.gd/86Z6x',
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
