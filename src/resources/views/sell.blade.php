@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css')}}">
@endsection

@section('link')
    <div class = "header-container">
        <form class = "search" action = "/search" method = "GET">
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
<div class="sell__group">
    <h2>商品の出品</h2>

    <form class = "sell__form" action="/sell" method="post" enctype="multipart/form-data">
        @csrf
        <div class="image-upload">
            <p class="image-title">商品画像</p>
            <label class="image-box" for="item_image">
                <span class="image-button">画像を選択する</span>
                <input type="file" name="item_image" id="item_image" accept="image/*" hidden>
            </label>
        </div>

        <div class="sell-details">
            <h3>商品の詳細</h3>
                <div class="category__group">
                    <label>カテゴリー</label>
                    <!-- チェックボックスで複数カテゴリ選択 -->
                    <div class="categories">
                        @foreach($categories as $category)
                            <div class="category-btn__group">
                                <input class = "category-btn" type="checkbox" name="category_ids[]" value="{{ $category->id }}" id="category_{{ $category->id }}">
                                <label for="category_{{ $category->id }}">{{ $category->content }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

            <div class="sell-condition">
                <label for="condition">商品の状態</label>
                <div class="select-wrapper">
                    <select name="condition" id="condition">
                        <option value="" disabled selected>選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                </div>
            </div>

            <div class="sell-description">
                <h3>商品名と説明</h3>
                <div class="form-group">
                <label for="sell-name">商品名</label>
                <input class = "sell-input" type="text" name="item_name" id="sell-name" required />
                </div>

                <div class="form-group">
                    <label for="sell-description">商品の説明</label>
                    <textarea name="item_detail" id="sell-description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="price">販売価格</label>
                    <div class="price-input-wrapper">
                        <span class="currency-symbol">￥</span>
                        <input class="sell-input" type="text" name="price" id="price" required />
                    </div>
                </div>

                <div class="sell-btn">
                    <button class="sell-btn btn" type="submit">出品する</button>
                </div>
            </div>
    </form>
</div>
@endsection