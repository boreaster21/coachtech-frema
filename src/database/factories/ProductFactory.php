<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use Illuminate\Support\Facades\File;
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

        $images = File::exists(storage_path('app/public/product-icons')) ? File::files(storage_path('app/public/product-icons')) : [];
        $randomImage = $images ? $images[array_rand($images)]->getFilename() : null;

        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $user->id,
            'product_photo_path' => $randomImage ? 'storage/product-icons/' . $randomImage : null,
            'name' => $faker->word,
            'brand_name' => $faker->word,
            'description' => $faker->sentence,
            'price' => $this->faker->numberBetween(100, 10000),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($product) {
            // カテゴリと状態を中間テーブルに登録
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
