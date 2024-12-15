<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->prepend(\Illuminate\Cookie\Middleware\EncryptCookies::class); // クッキー暗号化
        // $middleware->prepend(\Illuminate\Session\Middleware\StartSession::class); // セッション開始
        // $middleware->append(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class); // CSRFトークン検証
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 必要に応じて設定
    })
    ->create();
