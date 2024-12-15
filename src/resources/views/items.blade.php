@extends('layouts.template')
@section('title', $product->name . ' - 商品詳細')
@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@section('content')
<div class="container">
    <!-- 商品画像 -->
    <div class="product-image">
        <a href="{{ route('product.show', $product->id) }}">
            <img src="{{ asset($product->product_photo_path) }}" alt="{{ $product->name }}">
        </a>
    </div>

    <!-- 商品詳細 -->
    <div class="product-details">
        <h1>{{ $product->name }}</h1>
        <p>{{ $product->brand_name }}</p>
        <h2>{{ number_format($product->price) }}円</h2>

        <!-- お気に入りとコメントアイコン -->
        <div class="icons">
            <!-- お気に入りアイコン -->
            <form action="{{ route('item.favorite', $product->id) }}" method="POST"
                @guest onclick="return false;" @endguest>
                @csrf
                <button type="submit" class="favorite-button" title="お気に入り">
                    <i class="{{ Auth::check() && Auth::user()->favorites->contains($product->id) ? 'fas fa-star text-yellow-500' : 'far fa-star' }}"></i>
                </button>
            </form>

            <!-- コメントアイコン -->
            <a href="{{ route('item.comments', $product->id) }}" class="comment-icon" title="コメント">
                <i class="fa fa-comment"></i>
            </a>
        </div>

        <!-- 購入ボタン -->
        <a href="{{ route('purchase', $product->id) }}" class="buy-button">購入する</a>

        <!-- 商品説明 -->
        <h3>商品説明</h3>
        <p>{{ $product->description }}</p>

        <!-- 商品情報 -->
        <div class="product-info">
            <dl>
                <dt>カテゴリー</dt>
                <dd>
                    @if($product->categories && $product->categories->isNotEmpty())
                    @foreach($product->categories as $category)
                    {{ $category->name }}@if(!$loop->last)、@endif
                    @endforeach
                    @else
                    カテゴリー情報がありません
                    @endif
                </dd>

                <dt>商品の状態</dt>
                <dd>
                    @if($product->condition && $product->condition->isNotEmpty())
                    @foreach ($product->condition as $condition)
                    {{ $condition->name }}@if (!$loop->last)、@endif
                    @endforeach
                    @else
                    状態情報がありません
                    @endif
                </dd>
            </dl>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.favorite-icon').forEach(icon => {
            icon.addEventListener('click', async () => {
                const productId = icon.getAttribute('data-product-id');

                // サーバー側にリクエストを送信してお気に入り状態を切り替え
                const response = await fetch(`/product/${productId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    icon.classList.toggle('favorited'); // スタイルを変更
                }
            });
        });
    });
</script>
@endsection