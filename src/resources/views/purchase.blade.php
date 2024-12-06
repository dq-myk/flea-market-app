@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('link')
    <div class = "header-container">
        <form class = "search-form" action = "/search" method = "GET">
        @csrf
            <div class ="search-form__item">
                <input class ="search-form__item-input" type = "text" name = "keyword"  placeholder="なにをお探しですか？" value="{{request('keyword')}}">
            </div>
        </form>
        <nav class="header-nav">
            <form action="/logout" method="post">
            @csrf
                <input class="header__link" type="submit" value="ログアウト">
            </form>
            <a class="header__link" href="/mypage">マイページ</a>
            <a class= "sell-button" href="/sell">出品</a>
        </nav>
    </div>
@endsection

@section('content')
<form action="/purchase/{{ $item->id }}/confirm" method="POST">
    @csrf
    <div class="purchase-container">
        <div class="item-info__group">
            <div class="info__group">
                <div class="item-info">
                    <img class="item-image" src="{{ asset($item->image_path) }}" alt="{{ $item->name }}">
                </div>
                <div class="item-name">
                    <h2>{{ $item->name }}</h2>
                    @if ($item->purchases()->exists())
                    <div class="item-status">Sold</div>
                    @endif
                    <p class="price">¥{{ number_format($item->price) }} (税込)</p>
                </div>
            </div>

            <div class="payment-info">
                <h3>支払い方法</h3>
                <select class="payment-method" name="payment_method" onchange="this.form.submit()">
                    <option value="">選択してください</option>
                    <option value="コンビニ払い" {{ old('payment_method', $paymentMethod ?? '') == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                    <option value="カード支払い" {{ old('payment_method', $paymentMethod ?? '') == 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
                </select>
            </div>

            <div class="delivery-info">
                <div class="delivery">
                    <h3>配送先</h3>
                    <input type="hidden" name="shipping_address" value="{{ $user->post_code }} {{ $user->address }} {{ $user->building }}">
                    <a class="address-change" href="/purchase/address/{{ $item->id }}">変更する</a>
                </div>
                <p>〒{{ $user->post_code }}</p>
                <p>{{ $user->address }}</p>
                <p>{{ $user->building }}</p>
            </div>
        </div>

        <div class="item-purchase">
            <table class="payment">
                <tr>
                    <td>商品代金</td>
                    <td>¥{{ number_format($item->price) }}</td>
                </tr>
                <tr>
                    <td>支払い方法</td>
                    <td>
                        {{ $paymentMethod ?? '未選択' }}
                    </td>
                </tr>
            </table>
            <button class="purchase-button" type="submit" formaction="/purchase/{{ $item->id }}/complete">購入する</button>
        </div>
    </div>
</form>
@endsection