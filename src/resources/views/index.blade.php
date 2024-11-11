@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
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
    <div class="tab-menu">
        <a href="/" class="tab tab__recommended {{ request()->tab == 'mylist' ? '' : 'active' }}">おすすめ</a>
        <a href="/?tab=mylist" class="tab tab__mylist {{ request()->tab == 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="tab-menu__inner">
    @if ($tab === 'mylist')
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
@endsection