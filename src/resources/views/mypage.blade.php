@extends('layouts.template')
@section('title', 'マイページ')
@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@section('content')
<div class="mypage-container">

    <div class="user-info">
        <div class="user-icon">
            <img
                src="{{ $user->profile_photo_path 
                    ? Storage::url($user->profile_photo_path) 
                    : 'https://cdn.discordapp.com/attachments/1320669348490383401/1320669463380627456/default_avatar.png?ex=67857788&is=67842608&hm=15fee9f54db62b17de009650414c5d8b3fa2b5e965cb2d29cb32bebdb69d2aef&' }}"
                alt="User Icon"
                class="comment-user-icon">
        </div>
        <h1 class="user-name">{{ $user->name }}さんのマイページ</h1>
        <a href="/profile" class="profile-edit-button">プロフィールを編集</a>
    </div>

    <div class="tab-menu">
        <a href="?tab=listed" class="{{ request('tab') === 'listed' ? 'active' : '' }}">出品した商品</a>
        <a href="?tab=purchased" class="{{ request('tab') === 'purchased' ? 'active' : '' }}">購入した商品</a>
    </div>

    @if (request('tab') === 'listed')
    <div class="product-grid">
        @forelse ($listedProducts as $product)
        <div class="product-item">
            <img src="{{ asset($product->product_photo_path) }}" alt="{{ $product->name }}">
            <p>{{ $product->name }}</p>
            <p>{{ number_format($product->price) }}円</p>
        </div>
        @empty
        <p>出品された商品はありません。</p>
        @endforelse
    </div>
    @endif

    @if (request('tab') === 'purchased')
    <div class="product-grid">
        @forelse ($purchasedProducts as $purchase)
        <div class="product-item">
            <img src="{{ asset($purchase->product_photo_path) }}" alt="{{ $purchase->name }}">
            <p>{{ $purchase->name }}</p>
            <p>{{ number_format($purchase->price) }}円</p>
            <p>購入日時: {{ $purchase->purchased_at->format('Y-m-d H:i:s') }}</p>
        </div>
        @empty
        <p>購入された商品はありません。</p>
        @endforelse
    </div>
    @endif

</div>
@endsection