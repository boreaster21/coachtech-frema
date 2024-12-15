@extends('layouts.template')

@section('title', 'パスワード再設定')

@section('hide_search', true)
@section('hide_nav', true)

@section('css')
<link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
@endsection

@section('content')
<div class="auth-container">
    <div class="text-container">
        <p>{{ __('パスワードをお忘れですか？メールアドレスを入力いただければ、新しいパスワードを設定するリンクをお送りします。') }}</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf
        <div class="form-group">
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="error-message" />
        </div>

        <div class="form-group">
            <x-primary-button class="submit-button">
                {{ __('パスワード再設定リンクを送信') }}
            </x-primary-button>
        </div>
    </form>
</div>
@endsection