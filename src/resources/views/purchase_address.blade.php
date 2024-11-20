@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase_address.css')}}">
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
<div class="edit-form">
    <h2 class="edit-form__heading content__heading">住所の変更</h2>

    <div class="edit-form__inner">
        <form class="edit-form__form" action="/purchase/address/{{ $item->id }}" method="POST">
        @csrf
            <div class="edit-form__group">
                <label class="edit-form__label" for="post_code">郵便番号</label>
                    <input class="edit-form__input" type="text" name="post_code" pattern="\d{3}-\d{4}">
            </div>

            <div class="edit-form__group">
                <label class="edit-form__label" for="address">住所</label>
                    <input class="edit-form__input" type="text" name="address">
            </div>

            <div class = "edit-form__group">
                <label class="edit-form__label" for="building">建物名</label>
                    <input class="edit-form__input" type="text" name="building" >
            </div>

            <div>
                <button class="edit-form__btn btn" type="submit">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection('content')