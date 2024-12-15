<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RoleSeeder;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_user_can_post_comment()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $commentData = [
            'content' => 'Great product!', // 修正: 正しいフィールド名
        ];

        $response = $this->actingAs($user)->post(route('item.comment.store', $product->id), $commentData);

        $response->assertStatus(302); // リダイレクトが成功
        $this->assertDatabaseHas('comments', [
            'content' => 'Great product!',
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
    }


    public function test_user_cannot_post_comment_without_content()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $commentData = ['content' => ''];

        $response = $this->actingAs($user)->post(route('item.comment.store', $product->id), $commentData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['content']);
    }

    public function test_comments_are_displayed_on_product_page()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]); // 商品とユーザーを関連付け
        $comment = Comment::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'content' => 'Great product!',
        ]);

        $response = $this->actingAs($user)->get(route('item.comments', $product));

        $response->assertStatus(200); // ステータス確認
        $response->assertSee($comment->content); // コメント本文確認
        $response->assertSee($comment->user->name); // 投稿者名確認
        $response->assertSee($comment->created_at->format('Y-m-d H:i')); // 日時確認
    }

    public function test_non_admin_cannot_delete_comment()
    {
        $userRole = Role::firstWhere('name', 'user');
        $user = User::factory()->create(['role_id' => $userRole->id]);
        $comment = Comment::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.deleteComment', $comment->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }
}
