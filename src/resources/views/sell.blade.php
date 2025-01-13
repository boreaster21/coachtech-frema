@extends('layouts.template')
@section('title', '商品を出品')
@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection
@section('content')

<div class="sell-container">
    <h1>商品の出品</h1>

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="product_image">商品画像</label>
            <input type="file" name="product_image" id="product_image" required>
        </div>

        <div>
            <h2>商品の詳細</h2>
        </div>

        <div class="form-group">
            <label for="category">カテゴリー</label>
            <select name="category" id="category" required>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="condition">商品の状態</label>
            <select name="condition" id="condition" required>
                @foreach ($conditions as $condition)
                <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <h2>商品名と説明</h2>
        </div>

        <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-group">
            <label for="brand_name">ブランド名</label>
            <input type="text" name="brand_name" id="brand_name">
        </div>

        <div class="form-group">
            <label for="description">商品の説明</label>
            <textarea name="description" id="description" rows="4" required></textarea>
        </div>

        <div>
            <h2>販売価格</h2>
        </div>

        <div class="form-group">
            <label for="price">販売価格</label>
            <div class="price-container">
                <span class="price-symbol">￥</span>
                <input type="number" name="price" id="price" min="0" required oninput="this.value = Math.abs(this.value)">
            </div>
        </div>

        <button type="submit" class="submit-button">出品する</button>
    </form>
</div>

@endsection