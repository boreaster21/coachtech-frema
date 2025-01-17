@extends('layouts.template')
@section('title', $product->name . ' - 商品詳細')
@section('css')
<link rel="stylesheet" href="{{ asset('css/comments.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@section('content')
<div class="product-container">
    <div class="product-image">
        <a href="{{ route('product.show', $product->id) }}">
            <img src="{{ asset($product->product_photo_path) }}" alt="{{ $product->name }}">
        </a>
    </div>

    <div class="product-details">
        <h1>{{ $product->name }}</h1>
        <p>{{ $product->brand_name }}</p>
        <h2>{{ number_format($product->price) }}円</h2>

        <div class="icons">
            <form action="{{ route('item.favorite', $product->id) }}" method="POST" @guest onclick="return false;" @endguest>
                @csrf
                <button type="submit" class="favorite-button" title="お気に入り">
                    <i class="{{ Auth::check() && Auth::user()->favorites->contains($product->id) ? 'fas fa-star text-yellow-500' : 'far fa-star' }}"></i>
                </button>
            </form>

            <a href="{{ route('item.comments', $product->id) }}" class="comment-icon" title="コメント">
                <i class="fa fa-comment"></i>
            </a>
        </div>

        <a href="{{ route('purchase', $product->id) }}" class="buy-button">購入する</a>

        <div class="chat-container">
            <div class="comment-list">
                @foreach ($comments as $comment)
                <div class="comment-item">
                    <img src="{{ $comment->user->profile_photo_path ? Storage::url($comment->user->profile_photo_path) : asset('/images/default_avatar.png') }}" alt="User Icon" class="comment-user-icon">
                    <div class="comment-content">
                        <span class="comment-user-name">{{ $comment->user->name }}</span>
                        <p class="comment-text">{{ e($comment->content) }}</p>
                        <span class="comment-date">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            @auth
            <form action="{{ route('item.comment.store', $product->id) }}" method="POST" class="comment-form">
                @csrf
                <textarea name="content" id="content" rows="3" required placeholder="コメントを入力してください"></textarea>
                <button type="submit" class="comment-submit-button">コメントを送信する</button>
            </form>
            @else
            <p>コメントを残すには、<a href="{{ route('login') }}">ログイン</a>してください。</p>
            @endauth
        </div>
    </div>
</div>
@endsection