<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_keyword_search()
    {
        Product::factory()->create(['name' => 'Test Product', 'description' => 'A great product']);
        Product::factory()->create(['name' => 'Another Product']);

        $response = $this->get(route('products.search', ['query' => 'Test']));
        $response->assertStatus(200);
        $response->assertSee('Test Product');
        $response->assertDontSee('Another Product');
    }

    public function test_price_filter()
    {
        Product::factory()->create(['name' => 'Cheap Product', 'price' => 1000]);
        Product::factory()->create(['name' => 'Expensive Product', 'price' => 5000]);

        $response = $this->get(route('products.search', ['price_min' => 2000, 'price_max' => 6000]));
        $response->assertStatus(200);
        $response->assertSee('Expensive Product');
        $response->assertDontSee('Cheap Product');
    }

    public function test_sorting()
    {
        Product::factory()->create(['name' => 'Product A', 'price' => 3000]);
        Product::factory()->create(['name' => 'Product B', 'price' => 1000]);
        Product::factory()->create(['name' => 'Product C', 'price' => 2000]);

        $response = $this->get(route('products.search', ['sort_by' => 'price_asc']));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['Product B', 'Product C', 'Product A']);
    }

    public function test_empty_search_results()
    {
        $response = $this->get(route('products.search', ['query' => 'NonExistentProduct']));
        $response->assertStatus(200);
        $response->assertSee('該当する商品が見つかりません');
    }
}
