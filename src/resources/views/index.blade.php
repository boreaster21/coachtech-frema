@extends('layouts.template')
@section('title', '商品一覧')
@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection
@section('content')

<div class="tab-menu">
    <a href="?tab=recommend" class="{{ request('tab') === 'recommend' ? 'active' : '' }}">おすすめ</a>
    <a href="?tab=mylist" class="{{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>

@if(session('status'))
<div class="alert alert-warning">
    {{ session('status') }}
</div>
@endif

<!-- タブの表示内容 -->
@if(request('tab') === 'recommend' || !request('tab'))
<section class="product-grid">
    @forelse ($products as $product)
    <div class="product-item">
        <a href="{{ route('product.show', $product->id) }}">
            <img src="{{ asset($product->product_photo_path) }}" alt="{{ $product->name }}">
            <p>{{ $product->name }}</p>
            <p>{{ number_format($product->price) }}円</p>
        </a>
    </div>
    @empty
    <p>該当する商品が見つかりませんでした。</p>
    @endforelse
</section>
@endif

@if(request('tab') === 'mylist')
<section class="product-grid">
    @forelse ($myFavorites ?? [] as $product)
    <div class="product-item">
        <a href="{{ route('product.show', $product->id) }}">
            <img src="{{ asset($product->product_photo_path) }}" alt="{{ $product->name }}">
            <p>{{ $product->name }}</p>
            <p>{{ number_format($product->price) }}円</p>
        </a>
    </div>
    @empty
    <p>マイリストに追加した商品はまだありません。</p>
    @endforelse
</section>
@endif


@endsection