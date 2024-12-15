@extends('layouts.template')

@section('title', 'ログイン')

@section('hide_search', true)
@section('hide_nav', true)

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login-container">
    <!-- セッションステータス -->
    @if(session('status'))
    <div class="alert alert-warning">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <!-- メールアドレス -->
        <div class="form-group">
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="error-message" />
        </div>

        <!-- パスワード -->
        <div class="form-group mt-4">
            <x-input-label for="password" :value="__('パスワード')" />
            <x-text-input id="password" class="input-field" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="error-message" />
        </div>

        <!-- ログインを継続する -->
        <div class="form-group mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="checkbox" name="remember">
                <span class="remember-text">{{ __('ログインを継続する') }}</span>
            </label>
        </div>

        <!-- ボタンとリンク -->
        <div class="form-group mt-6">
            <x-primary-button class="login-button">
                {{ __('ログインする') }}
            </x-primary-button>
            @if (Route::has('password.request'))
            <a class="forgot-password" href="{{ route('password.request') }}">
                {{ __('パスワードをお忘れですか?') }}
            </a>
            @endif
            <div>
                <a class="register-link" href="{{ route('register') }}">
                    {{ __('会員登録はこちら') }}
                </a>
            </div>
    </form>
</div>
@endsection