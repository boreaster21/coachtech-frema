@extends('layouts.template')

@section('title', '住所の変更')
@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-edit-container">
    <h1>住所の変更</h1>

    <form action="{{ route('address.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ old('postcode', Auth::user()->postcode) }}" required>
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ old('address', Auth::user()->address) }}" required>
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building" class="form-control" value="{{ old('building', Auth::user()->building) }}">
        </div>

        <button type="submit" class="btn btn-primary">更新する</button>
    </form>
</div>
@endsection