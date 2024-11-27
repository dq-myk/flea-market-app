@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('link')
<div class="header-container">
    <form class="search-form" action="/" method="GET">
        @csrf
        <div class="search-form__item">
            <input class="search-form__item-input" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ $keyword }}">
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
<div class="tab-menu">
    <a href="/?tab=home&keyword={{ $keyword }}" class="tab tab__home {{ $tab == 'home' ? 'active' : '' }}">おすすめ</a>
    <a href="/?tab=mylist&keyword={{ $keyword }}" class="tab tab__mylist {{ $tab == 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>

<div class="tab-menu__inner">
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
        @if ($item->purchases()->exists())
        <div class="item-status">Sold</div>
        @endif
    </div>
    @endforeach
</div>
@endsection
