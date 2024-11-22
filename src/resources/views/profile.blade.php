@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css')}}">
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
<div class="profile-view">
    <div class="profile-view__item">
        <!-- 既存の画像があれば表示 -->
        <div class="profile-img">
            @if ($user->image_path && file_exists(public_path($user->image_path)))
                <img class="profile-img__item" src="{{ asset($user->image_path) }}" alt="プロフィール画像">
            @else
                <!-- 画像がない場合は画像枠のみ表示 -->
                <div class="profile-img__item profile-img__item--no-image"></div>
            @endif
            <strong>{{ $user->name }}</strong>
        </div>

        <!-- プロフィール編集ボタン -->
        <div class="profile__edit__group">
            <form action="/mypage/profile" method="GET">
                <input class="profile__edit__btn" type = "submit" name="profile__edit" id="profileEdit">
                <label for="profileEdit" class="profile__edit__btn-label">プロフィールを編集</label>
            </form>
        </div>
    </div>

    <div class="profile__tab-menu">
        <a href="/mypage?tab=sell" class="profile__tab profile__tab__sell {{ $tab == 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="/mypage?tab=buy" class="profile__tab profile__tab__purchase {{ $tab == 'purchase' ? 'active' : '' }}">購入した商品</a>
    </div>

    <div class="profile__tab-menu__inner">
    @if ($tab === 'purchase')
        @foreach ($items as $item)
            <div class="item-content">
                <div class="item-img">
                    @if ($item->image_path)
                    <a href="/item/{{ $item->id }}">
                        <img src="{{ $item->image_path }}" alt="商品画像">
                    </a>
                    @endif
                </div>
                <div class="item-name">{{ $item->name }}</div>
            </div>
        @endforeach
    @else
        @foreach ($items as $item)
            <div class="item-content">
                <div class="item-img">
                    @if ($item->image_path)
                    <a href="/item/{{ $item->id }}">
                        <img src="{{ $item->image_path }}" alt="商品画像">
                    </a>
                    @endif
                </div>
                <div class="item-name">{{ $item->name }}</div>
            </div>
        @endforeach
    @endif
    </div>
</div>
@endsection