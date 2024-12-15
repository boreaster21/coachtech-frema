@extends('layouts.template')
@section('title', '購入確認')
@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection
@section('content')

<div class="purchase-confirm-container">
    <div class="left-section">
        <div class="product-info">
            <div class="product-image">
                <img src="{{ asset($product->product_photo_path) }}" alt="{{ $product->name }}">
            </div>
            <div class="product-details">
                <h1>{{ $product->name }}</h1>
                <h2>{{ number_format($product->price) }}円</h2>
            </div>
        </div>

        <div class="additional-info">
            <table>
                <tr>
                    <th>支払い方法</th>
                    <td>
                        {{ session('payment_method', 'コンビニ払い') }}
                        <a href="{{ route('payment.edit', ['redirect_to' => route('purchase', $product->id)]) }}" class="btn btn-link">変更する</a>
                    </td>
                </tr>
                <tr>
                    <th>配送先</th>
                    <td>
                        {{ Auth::user()->postcode }}
                        {{ Auth::user()->address }}
                        <a href="{{ route('address.edit') }}" class="btn btn-link">変更する</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="purchase-summary">
        <table>
            <tr>
                <th>商品代金</th>
                <td>{{ number_format($product->price) }}円</td>
            </tr>
            <tr>
                <th>支払い金額</th>
                <td>{{ number_format($product->price) }}円</td>
            </tr>
            <tr>
                <th>支払い方法</th>
                <td>{{ session('payment_method', 'コンビニ払い') }}</td>
            </tr>
        </table>

        @if(session('payment_method') === 'Stripe')
        <!-- Stripe決済ボタン -->
        <form id="stripe-form" action="{{ route('purchase.stripe', $product->id) }}" method="POST">
            @csrf
            <button id="stripe-button" class="purchase-button">購入を確定する</button>
        </form>
        @else
        <!-- 通常の購入ボタン -->
        <form action="{{ route('purchase.success', $product->id) }}" method="POST">
            @csrf
            <button class="purchase-button">購入を確定する</button>
        </form>

        @endif
    </div>


    @if(session('payment_method') === 'Stripe')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Stripeキーを正しく取得
        const stripeKey = '{{ config("services.stripe.key") }}';
        console.log("Stripe Publishable Key:", stripeKey);
        const stripe = Stripe(stripeKey);

        document.getElementById('stripe-button').addEventListener('click', async (e) => {
            e.preventDefault(); // デフォルトのフォーム送信を防ぐ

            try {
                // サーバーにリクエストを送信してセッションIDを取得
                const response = await fetch("{{ route('purchase.stripe', $product->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        product_id: "{{ $product->id }}"
                    })
                });

                const data = await response.json();
                console.log("Stripe Checkout Session Response:", data); // ここでセッションIDを確認

                if (response.ok) {
                    // Stripe Checkoutにリダイレクト
                    await stripe.redirectToCheckout({
                        sessionId: data.sessionId
                    });
                } else {
                    alert("エラーが発生しました: " + (data.error || "不明なエラー"));
                }
            } catch (error) {
                alert("通信中にエラーが発生しました: " + error.message);
            }
        });
    </script>
    @endif
</div>

@endsection