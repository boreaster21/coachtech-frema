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
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669464710217728/smpl.png?ex=678b6649&is=678a14c9&hm=816bd35493f7aafa3d3b04ec562ac659be2e12c68a2df5ac580f2890cb3d9553&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669465242763264/smpl1.png?ex=678b6649&is=678a14c9&hm=cc595c56f2fcc7e3370ee443e51ceaf37c06d56f28997102126acc7d859f11d9&',
            'https://cdn.discordapp.com/attachments/1320669348490383401/1320669465599410186/ampl2.png?ex=678b6649&is=678a14c9&hm=b8aa25c600ecaedb7ec35ab3e9cc3749c910f7a77a999c758affd98dbcea3157&',
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
