<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication; // トレイトをインポート

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication; // トレイトを使用

    /**
     * テストケースのセットアップ処理
     */
    protected function setUp(): void
    {
        parent::setUp();

        // 必要なシーディングを実行
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
        
    }
}
