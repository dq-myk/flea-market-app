@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item_detail.css')}}">
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
<div class="item-details">
    <div class="item-image">
    @if ($item->image_path)
        <img src="{{ $item->image_path }}" alt="商品画像">
    @endif
    </div>
    <div class="item-info">
        <h2>{{ $item->name }}</h2>
        <p>{{ $item->brand }}</p>
        <p>¥{{ number_format($item->price) }} (税込)</p>
        <div class="actions">
            <div class="info">
                <span>
                    <img class="icon" src="../../storage/app/public/images/星アイコン.png" alt="Like Icon" >
                    {{ $item->likes_count }}
                </span>
                <span>
                    <img class="icon" src="../../storage/app/public/images/吹き出しアイコン.png" alt="View Icon" >
                    {{ $item->views_count }}
                </span>
            </div>
            <button class="purchase-button">購入手続きへ</button>
        </div>
        <div class="description">
            <h3>商品説明</h3>
            <p>カラー：{{ $item->color }}</p>
            <p>状態：{{ $item->condition }}</p>
            <p>{{ $item->description }}</p>
        </div>
        <div class="additional-info">
            <h3>商品の情報</h3>
            <p>カテゴリー: {{ $item->category->content }}</p>
            <p>商品状態: {{ $item->condition->content }}</p>
        </div>
        <div class="comments-section">
            <h3>コメント ({{ $item->comments->count() }})</h3>
            @foreach($item->comments as $comment)
                <div class="comment">
                    <strong>{{ $comment->user->name }}</strong>
                    <p>{{ $comment->content }}</p>
                </div>
            @endforeach
            <form action="/item/{item_id}" method="POST">
                @csrf
                <textarea name="content" placeholder="商品のコメント"></textarea>
                <button type="submit" class="comment-submit-button">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection