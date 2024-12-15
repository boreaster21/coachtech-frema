<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/dashboard'); // 登録後、ダッシュボードにリダイレクト
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']); // DBにユーザーが登録されているか確認
    }

    /** @test */
    public function a_user_cannot_register_with_duplicate_email()
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response = $this->post('/register', [
            'name' => 'Another User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email'); // メールアドレスのエラーを確認
    }

    /** @test */
    public function a_user_cannot_register_with_missing_fields()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '', // 必須項目が欠けている
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email'); // 必須エラー
    }
}
