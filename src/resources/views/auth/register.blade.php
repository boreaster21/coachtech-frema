@extends('layouts.template')

@section('title', '会員登録')

@section('hide_search', true)
@section('hide_nav', true)

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register-container">
    <h2 class="register-title">会員登録</h2>
    <form method="POST" action="{{ route('register') }}" class="register-form">
        @csrf

        <div class="form-group">
            <x-input-label for="name" :value="__('ユーザー名')" />
            <x-text-input id="name" class="form-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="error-message" />
        </div>

        <div class="form-group">
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="error-message" />
        </div>

        <div class="form-group">
            <x-input-label for="password" :value="__('パスワード')" />
            <x-text-input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="error-message" />
        </div>

        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('パスワードの確認')" />
            <x-text-input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
        </div>

        <div class="form-footer">
            <x-primary-button class="register-button">
                {{ __('登録する') }}
            </x-primary-button>
        </div>
        <div>
            <a class="login-link" href="{{ route('login') }}">
                {{ __('ログインはこちら') }}
            </a>
        </div>
    </form>
</div>
@endsection