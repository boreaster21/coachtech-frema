<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_product()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $condition = Condition::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'Test Product',
            'brand_name' => 'Test Brand',
            'description' => 'This is a test product description.',
            'price' => 1000,
            'category' => $category->id,
            'condition' => $condition->id,
            'product_image' => \Illuminate\Http\UploadedFile::fake()->image('product.jpg'),
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'brand_name' => 'Test Brand',
            'description' => 'This is a test product description.',
            'price' => 1000,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect('/');
    }

    public function test_validation_errors_on_product_creation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', []);

        $response->assertSessionHasErrors(['name', 'description', 'price', 'category', 'condition', 'product_image']);
    }

    public function test_products_are_displayed_on_index_page()
    {
        $product = Product::factory()->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee(number_format($product->price));
    }

    public function test_product_details_are_displayed_correctly()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('product.show', $product->id));

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->description);
        $response->assertSee(number_format($product->price));
    }
}
