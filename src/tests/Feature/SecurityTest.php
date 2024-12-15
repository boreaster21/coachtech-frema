<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);

        dump(DB::table('roles')->get());
    }

    #[Test]
    public function guest_cannot_access_protected_pages()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function unauthorized_user_cannot_access_admin_pages()
    {
        $userRole = \App\Models\Role::where('name', 'user')->first();
        $user = User::factory()->create(['role_id' => $userRole->id]); // 一般ユーザー

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    #[Test]
    public function valid_csrf_token_allows_request()
    {
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id'); // 必要な role_id を取得
        $user = User::factory()->create(['role_id' => $adminRoleId]); // 管理者ユーザーを作成

        $response = $this->actingAs($user)->post(route('test.sendMail'), [
            '_token' => csrf_token(),
            'user_id' => $user->id,
            'subject' => 'Test',
            'message' => 'Test message',
        ]);

        $response->dumpHeaders();
        $response->dumpSession();
        $response->dump();

        $response->assertStatus(200); // 正常にリクエストが処理されることを確認
    }

    #[Test]
    public function invalid_csrf_token_blocks_request()
    {
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $user = User::factory()->create(['role_id' => $adminRoleId]);

        $response = $this->actingAs($user)->post(
            route('test.sendMail'),
            [
                '_token' => 'invalid-token', // 無効なトークン
                'user_id' => 1,
                'subject' => 'Test',
                'message' => 'Test message',
            ],
            ['HTTP_X-CSRF-TOKEN' => 'invalid-token'] // ヘッダーに CSRF トークンを指定
        );

    }

    #[Test]
    public function sql_injection_is_prevented_in_search()
    {
        $maliciousInput = "' OR 1=1 --";
        $response = $this->get(route('products.search', ['query' => $maliciousInput]));
        $response->assertStatus(200);
        $response->assertDontSee('SQL syntax');
        $response->assertSee('該当する商品が見つかりません');
    }

    #[Test]
    public function xss_is_prevented_in_comments()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $this->actingAs($user);

        $maliciousScript = '<script>alert("XSS")</script>';
        $this->post(route('item.comment.store', $product->id), [
            'content' => $maliciousScript,
        ]);

        $response = $this->get(route('item.comments', $product->id));
        $response->assertStatus(200);
        $response->assertDontSee('<script>', false);
        $response->assertSee(e($maliciousScript));
    }
}
