@extends('layouts.template')
@section('title', 'Stripeでの決済')
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
    </div>

    <div class="purchase-summary">
        <table>
            <tr>
                <th>商品代金</th>
                <td>{{ number_format($product->price) }}円</td>
            </tr>
        </table>

        <form id="payment-form">
            <div id="card-element"></div>
            <button type="submit" class="purchase-button">購入を確定する</button>
        </form>

        <div id="payment-result" style="display: none;">
            <p id="payment-message"></p>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");

    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const resultDiv = document.getElementById('payment-result');
    const messageDiv = document.getElementById('payment-message');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const {
            paymentMethod,
            error
        } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            displayMessage(error.message);
            return;
        }

        try {
            const response = await fetch('{{ route("purchase.stripe", $product) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethod.id,
                }),
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || '不明なエラーが発生しました。');
            }

            const result = await response.json();

            if (result.success) {
                displayMessage("決済が完了しました。");
                await stripe.redirectToCheckout({
                    sessionId: result.sessionId,
                });
            } else {
                displayMessage(result.message || "決済に失敗しました。");
            }
        } catch (error) {
            displayMessage("通信中にエラーが発生しました: " + error.message);
        }
    });


    function displayMessage(message) {
        resultDiv.style.display = 'block';
        messageDiv.textContent = message;
    }
</script>
@endsection