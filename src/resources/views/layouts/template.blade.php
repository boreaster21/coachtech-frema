<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'coachtechフリマ')</title>
    @yield('css')
    <link rel="stylesheet" href="https://unpkg.com/sanitize.css" />
    <link rel="stylesheet" href="{{ asset('css/templete.css') }}">
</head>

<body>
    <!-- ヘッダー -->
    <header class="header">
        <a href="{{ url('/') }}" class="logo-container">
            <img src="{{ asset('images/logo_coachtech.svg') }}" class="logo" alt="coachtech" width="100" height="100">
        </a>

        <!-- 検索フォーム -->
        @unless(View::getSection('hide_search'))
        <form action="{{ route('products.search') }}" method="GET" class="search-form">
            <input type="text" name="query" class="search-box" placeholder="何をお探しですか？" value="{{ request('query') }}">
            <select name="sort_by" class="sort-box">
                <option value="relevance" {{ request('sort_by') == 'relevance' ? 'selected' : '' }}>関連度順</option>
                <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>価格が安い順</option>
                <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>価格が高い順</option>
                <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>新着順</option>
            </select>
        </form>
        @endunless

        <!-- ナビゲーションボタン -->
        @unless(View::getSection('hide_nav'))
        <nav class="nav-buttons">
            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
            <a href="{{ url('/mypage') }}">マイページ</a>
            <a href="{{ route('product.create') }}">出品</a>
            @else
            <a href="{{ route('login') }}">ログイン</a>
            <a href="{{ route('register') }}">会員登録</a>
            <a href="{{ route('product.create') }}">出品</a>
            @endauth
        </nav>
        @endunless
    </header>

    <!-- コンテンツ -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- フッター -->
    <footer class="footer-content">
        <p>&copy; 2024 coachtechFURIMA. All rights reserved.</p>
    </footer>

</body>

</html>