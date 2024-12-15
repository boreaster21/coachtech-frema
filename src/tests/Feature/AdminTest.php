<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use app\Mail\UserNotificationMail;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class); // 役割をシード
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $adminRole = Role::firstWhere('name', 'admin');
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('管理画面');
    }

    public function test_non_admin_cannot_access_admin_dashboard()
    {
        $userRole = Role::firstWhere('name', 'user');
        $user = User::factory()->create(['role_id' => $userRole->id]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403); // 一般ユーザーには権限がない
    }

    public function test_admin_can_delete_user()
    {
        $adminRole = Role::firstWhere('name', 'admin');
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $response = $this->actingAs($admin)->delete(route('admin.deleteUser', $user->id));

        $response->assertStatus(302); // 削除後リダイレクト
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_deleted_user_cannot_login()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $user->delete(); // ユーザーを削除

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password', // ファクトリーで生成したデフォルトパスワード
        ]);

        $response->assertSessionHasErrors(); // ログイン失敗
    }

    public function test_admin_can_delete_comment()
    {
        $adminRole = Role::firstWhere('name', 'admin');
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $comment = Comment::factory()->create();

        $this->assertDatabaseHas('comments', ['id' => $comment->id]);

        $response = $this->actingAs($admin)->delete(route('admin.deleteComment', $comment->id));

        $response->assertStatus(302); // 削除後リダイレクト
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_admin_can_send_email_to_user()
    {
        Mail::fake();

        $adminRole = Role::firstWhere('name', 'admin');
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.sendMail'), [
            'user_id' => $user->id,
            'subject' => '重要なお知らせ',
            'message' => 'これはテストメールです。',
        ]);

        $response->assertStatus(200);

        Mail::assertSent(\App\Mail\UserNotificationMail::class, function (\App\Mail\UserNotificationMail $mail) use ($user) {
            return $mail->hasTo($user->email) &&
                $mail->subject === '重要なお知らせ';
        });
    }


    public function test_email_validation_errors_are_handled()
    {
        $adminRole = Role::firstWhere('name', 'admin');
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($admin)->post(route('admin.sendMail'), [
            'user_id' => '',
            'subject' => '',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['user_id', 'subject', 'message']);
    }
}
