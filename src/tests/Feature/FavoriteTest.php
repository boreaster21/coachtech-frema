<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_product_to_favorites()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->post(route('item.favorite', $product->id))
            ->assertRedirect();

        $this->assertTrue($user->favorites->contains($product));
    }

    public function test_user_can_remove_product_from_favorites()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $user->favorites()->attach($product);

        $this->actingAs($user)
            ->post(route('item.favorite', $product->id))
            ->assertRedirect();

        $this->assertFalse($user->favorites->contains($product));
    }

    public function test_user_can_view_favorites_list()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $user->favorites()->attach($product);

        $this->actingAs($user)
            ->get(route('item.myFavorites'))
            ->assertStatus(200)
            ->assertSee($product->name)
            ->assertSee(number_format($product->price) . '円');
    }
}
