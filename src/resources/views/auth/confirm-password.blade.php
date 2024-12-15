@extends('layouts.template')

@section('title', 'パスワード確認')

@section('hide_search', true)
@section('hide_nav', true)

@section('css')
<link rel="stylesheet" href="{{ asset('css/confirm-password.css') }}">
@endsection

@section('content')
<div class="auth-container">
    <div class="text-container">
        <p>{{ __('アプリケーションの保護された領域にアクセスするには、パスワードを確認してください。') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf
        <div class="form-group">
            <x-input-label for="password" :value="__('パスワード')" />
            <x-text-input id="password" class="input-field" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="error-message" />
        </div>

        <div class="form-group">
            <x-primary-button class="submit-button">
                {{ __('確認する') }}
            </x-primary-button>
        </div>
    </form>
</div>
@endsection