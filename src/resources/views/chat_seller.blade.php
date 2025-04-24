@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat_seller.css')}}">
@endsection

@section('content')
<div class="chat_seller">
    <aside class="sidebar">その他の取引</aside>
        <div class="trade-header">
            <div class="user-icon"></div>
            <div class="title">「ユーザー名」さんとの取引画面</div>
            <button class="complete-btn">取引を完了する</button>
        </div>

        <div class="product-box">
            <div class="product-image">商品画像</div>
            <div class="product-info">
                <h2>商品名</h2>
                <a href="#">商品価格</a>
            </div>
        </div>

        <div class="message-box">
            <div class="message user">
                <div class="icon"></div>
                <div>
                    <div class="username">ユーザー名</div>
                    <div class="message-content">（メッセージ内容）</div>
                </div>
            </div>

            <div class="message other">
                <div class="icon"></div>
                <div>
                    <div class="username">ユーザー名</div>
                    <div class="message-content">（メッセージ内容）</div>
                    <div class="actions">
                        <a href="#">編集</a>
                        <a href="#">削除</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="message-input">
            <input type="text" placeholder="取引メッセージを記入してください">
            <button class="image-btn">画像を追加</button>
            <button class="send-btn">📨</button>
        </div>
    </div>
</body>
</html>
@endsection