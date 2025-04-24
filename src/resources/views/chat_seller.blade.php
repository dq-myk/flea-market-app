@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat_seller.css')}}">
@endsection

@section('content')
<div class="chat_seller">
    <aside class="sidebar">
        <p>その他の取引</p>
        <button class="item-btn">商品名</button>
        <button class="item-btn">商品名</button>
        <button class="item-btn">商品名</button>
    </aside>

    <div class="main-content">
        <div class="chat-header">
            <div class="avatar">
                @if ($transaction->buyer && $transaction->buyer->image_path)
                    <img class="avatar-img__item" src="{{ asset($transaction->buyer->image_path) }}" alt="プロフィール画像">
                @else
                    <div class="avatar-img__item avatar-img__item--no-image"></div>
                @endif
            </div>
            @if($transaction && $transaction->buyer)
                <p>「{{ $transaction->buyer->name }}」さんとの取引画面</p>
            @endif
        </div>

        <section class="product-info">
            <div class="product-image">
                <img class="product-img__item" src="{{ asset($transaction->item->image_path) }}" alt="商品画像">
            </div>
            <div class="product-details">
                <h1>商品名</h1>
                <p>商品価格</p>
            </div>
        </section>

        <section class="chat-box">
            <div class="message left">
                <div class="avatar">
                    @if ($transaction->seller && $transaction->seller->image_path)
                        <img class="avatar-img__item" src="{{ asset($transaction->seller->image_path) }}" alt="プロフィール画像">
                    @else
                        <div class="avatar-img__item avatar-img__item--no-image"></div>
                    @endif
                </div>
                <div>
                <p class="username">ユーザー名</p>
                <div class="bubble">メッセージ内容</div>
                </div>
            </div>

            <div class="message right">
                <div>
                <p class="username">ユーザー名</p>
                <div class="bubble">自分が送ったメッセージ</div>
                <div class="actions">
                    <span>編集</span>
                    <span>削除</span>
                </div>
                </div>
                <div   div class="avatar">
                    @if ($transaction->buyer && file_exists(public_path($transaction->buyer->image_path)))
                        <img class="avatar-img__item" src="{{ asset($transaction->buyer->image_path) }}" alt="プロフィール画像">
                    @else
                        <div class="avatar-img__item avatar-img__item--no-image"></div>
                    @endif
                </div>
            </div>
        </section>

        <footer class="chat-footer">
            <p class="error-message">
                @if ($errors->has('message.' . $index))
                    <p class="detail__error-message-message">{{ $errors->first('message.' . $index) }}</p>
                @endif
                @if ($errors->has('image.' . $index))
                    <p class="detail__error-message-image">{{ $errors->first('image.' . $index) }}</p>
                @endif
            </p>
        <form action="{{ route('/chat/send/' . $transaction->transaction->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="message" placeholder="取引メッセージを記入してください" />
            <input type="file" name="image" accept="image/*" style="display: none;" id="imageInput">
            <label for="imageInput" class="image-btn">画像を追加</label>
            <button class="send-btn" type="submit">
                <img class="icon" src="{{ asset('storage/images/sending.jpg') }}" alt="sending_icon">
            </button>
        </form>
</footer>
    </div>
</div>
@endsection