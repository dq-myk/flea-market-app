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
<div class="purchase-container">
    <div class = "item-info__group">
        <div class="item-info">
            <div class="item-image">
            <img src="{{ asset($item->image_path) }}" alt="{{ $item->name }}">
        </div>
        <div class="item-name">
            <h2>{{ $item->name }}</h2>
            <p class = "price">¥{{ number_format($item->price) }} (税込)</p>
        </div>
    </div>

        <div class="payment-info">
            <h3>支払い方法</h3>
            <select>
                <option value="">選択してください</option>
                <option value="コンビニ払い">コンビニ払い</option>
                <option value="クレジットカード">クレジットカード</option>
            </select>
        </div>

        <div class="delivery-info">
            <h3>配送先</h3>
            <p>{{ $user['address'] }}</p>
            <p>{{ $user['address_details'] }}</p>
            <a href="#">変更する</a>
        </div>
    <div class="item-purchase">
        <table>
            <tr>
                <td>商品代金</td>
                <td>¥ {{ number_format($item['price']) }}</td>
            </tr>
            <tr>
                <td>支払い方法</td>
                <td>コンビニ払い</td>
            </tr>
        </table>
        <button>購入する</button>
    </div>
</div>
@endsection