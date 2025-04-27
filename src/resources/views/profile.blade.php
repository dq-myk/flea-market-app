@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css')}}">
@endsection

@section('link')
<div class="header-container">
    <form class="search" action="/search" method="GET">
        @csrf
        <div class="search-form__item">
            <input class="search-form__item-input" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{request('keyword')}}">
        </div>
    </form>
    <nav class="header-nav">
        <form action="/logout" method="post">
            @csrf
            <input class="header__link" type="submit" value="ログアウト">
        </form>
        <a class="header__link" href="/mypage">マイページ</a>
        <a class="sell-button" href="/sell">出品</a>
    </nav>
</div>
@endsection

@section('content')
<div class="profile-view">
    <div class="profile-view__item">
        <div class="profile-img">
            @if ($user->image_path)
            <img class="profile-img__item" src="{{ asset($user->image_path) }}" alt="プロフィール画像">
            @else
            <div class="profile-img__item profile-img__item--no-image"></div>
            @endif
            <div class="user-info">
                <strong class="user-name">{{ $user->name }}</strong>
                <div class="user-rating">
                    @php
                        // 出品者か購入者かを判断
                        $isSeller = $user->is_seller; // 出品者かどうかを判定するフラグ
                        // 出品者は購入者からの評価平均、購入者は出品者からの評価平均を取得
                        $rating = $isSeller
                            ? round(\App\Models\UserReview::where('reviewee_id', $user->id)->avg('rating')) // 購入者からの評価
                            : round(\App\Models\UserReview::where('reviewer_id', $user->id)->avg('rating')); // 出品者からの評価
                    @endphp

                    @if ($rating > 0) <!-- 評価がある場合のみ表示 -->
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $rating ? 'filled' : 'empty' }}">★</span> <!-- 評価に応じてクラスを切り替え -->
                        @endfor
                    @endif
                </div>
            </div>
        </div>

        <div class="profile__edit__group">
            <form action="/mypage/profile" method="GET">
                <input class="profile__edit__btn" type="submit" name="profile__edit" id="profileEdit">
                <label for="profileEdit" class="profile__edit__btn-label">プロフィールを編集</label>
            </form>
        </div>
    </div>

    <div class="profile__tab-menu">
        <a href="/mypage?tab=sell" class="profile__tab profile__tab__sell {{ $tab == 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="/mypage?tab=buy" class="profile__tab profile__tab__buy {{ $tab == 'buy' ? 'active' : '' }}">購入した商品</a>
        <a href="/mypage?tab=trade" class="profile__tab profile__tab__trade {{ $tab == 'trade' ? 'active' : '' }}">
            取引中の商品
            @if($totalUnread > 0)
                <span class="tab-badge">{{ $totalUnread }}</span>
            @endif
        </a>
    </div>

    <div class="profile__tab-menu__inner">
        @if ($tab === 'buy')
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

        @elseif ($tab === 'sell')
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
                    @php
                        // 出品者か購入者かを判断
                        $isSeller = ($item->transaction->seller_id === auth()->user()->id);
                    @endphp
                    <a href="{{ $isSeller ? '/chat/seller/' . $item->transaction->id : '/chat/buyer/' . $item->transaction->id }}">
                        <img src="{{ $item->image_path }}" alt="商品画像">
                        @if($item->unread_messages_count > 0)
                            <span class="image-badge">{{ $item->unread_messages_count }}</span>
                        @endif
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