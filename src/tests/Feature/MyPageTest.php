<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_mypage_lists_listed_products()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get('/mypage?tab=listed')
            ->assertStatus(200)
            ->assertSee($product->name)
            ->assertSee(number_format($product->price) . '円');
    }

    public function test_mypage_shows_message_when_no_listed_products()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/mypage?tab=listed')
            ->assertStatus(200)
            ->assertSee('出品された商品はありません。');
    }

    public function test_mypage_lists_purchased_products()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $purchaseTime = now();
        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'purchased_at' => $purchaseTime,
            'status' => 'completed',
        ]);

        $this->actingAs($user)
            ->get('/mypage?tab=purchased')
            ->assertStatus(200)
            ->assertSee($product->name)
            ->assertSee(number_format($product->price) . '円')
            ->assertSee($purchaseTime->format('Y-m-d H:i:s')); // 購入日時の確認
    }


    public function test_mypage_shows_message_when_no_purchased_products()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/mypage?tab=purchased')
            ->assertStatus(200)
            ->assertSee('購入された商品はありません。');
    }

    public function test_other_users_data_is_not_visible()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user2->id]);
        $user2->purchases()->attach($product->id, [
            'purchased_at' => now(),
            'status' => 'completed',
        ]);

        $this->actingAs($user1)
            ->get('/mypage?tab=listed')
            ->assertStatus(200)
            ->assertDontSee($product->name);

        $this->actingAs($user1)
            ->get('/mypage?tab=purchased')
            ->assertStatus(200)
            ->assertDontSee($product->name);
    }
}
