<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Role;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_purchase_page()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->get(route('purchase', $product->id));
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_user_can_complete_purchase()
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Stripe のモック
        $this->mockStripeSuccess();

        $response = $this->actingAs($user)->post(route('purchase.stripe', $product->id), [
            'payment_method' => 'test_payment_method',
        ]);

        $response->assertRedirect('http://localhost/purchase/success/2');
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }


    public function test_purchased_product_cannot_be_purchased_again()
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $user = User::factory()->create();
        $product = Product::factory()->create();

        // 購入済みとして記録
        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'purchased_at' => now(),
        ]);

        $response = $this->actingAs($user)->post(route('purchase.stripe', $product->id));
        $response->assertStatus(403);
    }

    public function test_purchase_history_is_displayed_on_mypage()
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $user = User::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        Purchase::create(['user_id' => $user->id, 'product_id' => $product1->id, 'purchased_at' => now()]);
        Purchase::create(['user_id' => $user->id, 'product_id' => $product2->id, 'purchased_at' => now()]);

        $response = $this->actingAs($user)->get(route('mypage', ['tab' => 'purchased']));
        $response->assertStatus(200);
        $response->assertSee($product1->name);
        $response->assertSee($product2->name);
    }

    private function mockStripeSuccess()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $mockSession = \Mockery::mock('overload:' . \Stripe\Checkout\Session::class);
        $mockSession->shouldReceive('create')->andReturn((object)[
            'id' => 'test_session_id',
            'url' => 'http://localhost/purchase/success/2',
        ]);
    }


}
