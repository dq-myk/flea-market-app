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
        <img src="{{ asset($item->image_path) }}" alt="{{ $item->name }}">
    </div>
    <div class="item-detail">
        <h2>{{ $item->name }}</h2>
        <p class = "brand">{{ $item->brand }}</p>
        <p class = "price">¥{{ number_format($item->price) }} (税込)</p>
        <div class="item-purchase__group">
            <div class="item-purchase__group-item">
                <form action="/item/{{ $item->id }}/like" method="POST">
                @csrf
                <button type="submit" class="like-button">
                    @if ($isLiked)
                <img class="icon" src="{{ asset('storage/images/星アイコン黄色.png') }}" alt="like_icon">
            @else
                <img class="icon" src="{{ asset('storage/images/星アイコン.png') }}" alt="like_icon">
            @endif
            {{ $item->likes_count }}
                </button>
                </form>
                <span>
                    <img class="icon" src="{{ asset('storage/images/吹き出しアイコン.png') }}" alt="comment_icon" >
                    {{ $item->comments_count }}
                </span>
            </div>
            <button class="purchase-button btn">購入手続きへ</button>
        </div>
        <div class="item__description">
            <h3>商品説明</h3>
            <p>カラー：{{ $item->color }}</p>
            <div class ="status__group">
                <p>{{ $item->status }}</p>
                <p>{{ $item->status_comment }}</p>
            </div>
            <p>購入後、即発送いたします。</p>
        </div>
        <div class="item__additional-info">
            <h3>商品の情報</h3>
            <div class = "category__group">
                <p>カテゴリー&ensp;&ensp;
                    <p class = "category-item">
                        @foreach ($item->categories as $category)
                            <p class = "category">
                                {{ $category->content }}
                            </p>
                        @if (!$loop->last)
                            &ensp;&ensp;
                        @endif
                        @endforeach
                    </p>
                </p>
            </div>
            <p>商品の状態 &ensp;&ensp;&ensp;{{ $item->condition }}</p>
        </div>
        <div class="comments-section">
            <h3>コメント ({{ $item->comments->count() }})</h3>
            @foreach($item->comments as $comment)
                <div class="comment-view">
                    <!-- 既存の画像があれば表示 -->
                    <div class="profile-img">
                        @if ($user->image_path && file_exists(public_path($user->image_path)))
                            <img class="profile-img__item" src="{{ asset($user->image_path) }}" alt="プロフィール画像">
                        @else
                            <!-- 画像がない場合は画像枠のみ表示 -->
                            <div class="profile-img__item profile-img__item--no-image"></div>
                        @endif
                    </div>
                    <strong>{{ $comment->user->name }}</strong>
                    <p>{{ $comment->content }}</p>
                </div>
            @endforeach
            <form action="/item/{item_id}" method="POST">
                @csrf
                <h3>商品へのコメント</h3>
                <textarea name="content"></textarea>
                <button  class="comment-submit-button btn" type="submit">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection