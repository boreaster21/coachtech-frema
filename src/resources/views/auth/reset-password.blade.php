@extends('layouts.template')

@section('title', 'パスワードリセット')

@section('hide_search', true)
@section('hide_nav', true)

@section('css')
<link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">
@endsection

@section('content')
<div class="auth-container">
    <form method="POST" action="{{ route('password.store') }}" class="auth-form">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="error-message" />
        </div>

        <div class="form-group">
            <x-input-label for="password" :value="__('新しいパスワード')" />
            <x-text-input id="password" class="input-field" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="error-message" />
        </div>

        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('新しいパスワード（確認）')" />
            <x-text-input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
        </div>

        <div class="form-group">
            <x-primary-button class="submit-button">
                {{ __('パスワードをリセット') }}
            </x-primary-button>
        </div>
    </form>
</div>
@endsection