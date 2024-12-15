@extends('layouts.template')

@section('title', 'プロフィール設定')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile-common.css') }}">
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection

@section('content')
<div class="profile-container">
    <h1 class="profile-title"></h1>

    <div class="profile-content">
        <!-- プロフィール情報の更新フォーム -->
        <div class="profile-section">
            <div class="profile-section-content">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- パスワード変更フォーム -->
        <div class="profile-section">
            <div class="profile-section-content">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- アカウント削除フォーム -->
        <div class="profile-section">
            <div class="profile-section-content">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection