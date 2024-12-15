@extends('layouts.template')
@section('title', '支払い方法の変更')
@section('css')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('content')
<div class="payment-edit-container">
    <h1>支払い方法の変更</h1>

    <form action="{{ route('payment.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="payment_method">支払い方法を選択してください:</label>
            <select name="payment_method" id="payment_method">
                @foreach ($payments as $payment)
                <option value="{{ $payment->method_name }}"
                    {{ session('payment_method') == $payment->method_name ? 'selected' : '' }}>
                    {{ $payment->method_name }}
                </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">支払い方法を更新する</button>
    </form>
</div>
@endsection