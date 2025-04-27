@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_edit.css')}}">
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
<div class="profile__edit">
    <h2 class="profile__edit__heading content__heading">プロフィール設定</h2>
    <div class="profile__edit__inner">
        <form  class="profile__edit__form" action="/mypage/profile" enctype="multipart/form-data"  method="POST" >
        @csrf
            <div class="profile-img__group">
                <div class="profile-img__inner">
                    <!-- 既存の画像があれば表示 -->
                    <div class="profile-img">
                        @if ($user->image_path && file_exists(public_path($user->image_path)))
                            <img class="profile-img__item" src="{{ asset($user->image_path) }}" alt="プロフィール画像">
                        @else
                            <!-- 画像がない場合は画像枠のみ表示 -->
                            <div class="profile-img__item profile-img__item--no-image"></div>
                        @endif
                    </div>

                    <!-- 画像選択ボタン -->
                    <div class="profile-img__select">
                        <input class="profile-img__btn" type="file" name="profile_image" accept="image/*" id="imgInput">
                        <label class="profile-img__btn-label" for="imgInput">画像を選択する</label>
                    </div>
                </div>
                <p class="profile_image__error-message">
                    @error('profile_image')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile__edit__group">
                <label class="profile__edit__label" for="name">ユーザー名</label>
                <input class="profile__edit__input" type="text" name="name" value="{{ old('name') ?? $user->name }}">
            </div>

            <div class="profile__edit__group">
                <label class="profile__edit__label" for="post_code">郵便番号</label>
                <input class="profile__edit__input" type="text" name="post_code" pattern="\d{3}-\d{4}" value="{{ old('post_code', $user->post_code) }}">
            </div>

            <div class="profile__edit__group">
                <label class="profile__edit__label" for="address">住所</label>
                <input class="profile__edit__input" type="text" name="address" value="{{ old('address', $user->address) }}">
            </div>

            <div class="profile__edit__group">
                <label class="profile__edit__label" for="building">建物名</label>
                <input class="profile__edit__input" type="text" name="building" value="{{ old('building', $user->building) }}">
            </div>

            <div>
                <button class="profile__edit__btn btn" type="submit">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection