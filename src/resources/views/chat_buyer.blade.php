@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat_buyer.css')}}">
@endsection

@section('content')
<div class="chat_buyer">
        <aside class="sidebar">
            <p>その他の取引</p>
            @foreach ($tradingItems as $item)
            <a href="/chat/buyer/{{ $item->id }}">
                <button class="item-btn">
                    {{ $item->item->name }}
                </button>
            </a>
        @endforeach
        </aside>

    <div class="main-content">
        <div class="chat-header">
            <div class="avatar">
                @if ($transaction->seller && $transaction->seller->image_path)
                    <img class="avatar-img__item" src="{{ asset($transaction->seller->image_path) }}" alt="プロフィール画像">
                @else
                    <div class="avatar-img__item avatar-img__item--no-image"></div>
                @endif
            </div>
            <div class="chat-header__info">
                <div class="chat-header__text">
                    @if($transaction && $transaction->seller)
                        <p>「{{ $transaction->seller->name }}」さんとの取引画面</p>
                    @endif
                </div>

                <input type="checkbox" id="modal-toggle" class="modal-toggle" hidden>
                <label for="modal-toggle" class="complete-btn">取引を完了する</label>

                <div class="modal" id="completeModal">
                    <div class="modal-content">
                        <h2 class="modal-title">取引が完了しました。</h2>
                        <hr>
                        <p class="modal-question">今回の取引相手はどうでしたか？</p>

                        <form method="POST" action="/transaction/complete/{{ $transaction->id }}">
                        @csrf

                            <div class="stars">
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5">★</label>
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4">★</label>
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3">★</label>
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2">★</label>
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1">★</label>
                            </div>

                            <hr>

                            <div class="submit-area">
                                <button type="submit" class="submit-btn">送信する</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-info">
            <div class="product-image">
                <img class="product-img__item" src="{{ asset($transaction->item->image_path) }}" alt="商品画像">
            </div>
            <div class="product-details">
                <h1>{{ $transaction->item->name }}</h1>
                <p>¥{{ number_format($transaction->item->price) }} (税込)</p>
            </div>
        </div>

        <div class="chat-box">
            @foreach($transaction->messages as $message)
                <div class="message {{ $message->sender_id === auth()->id() ? 'right' : 'left' }}">
                    <div class="avatar">
                        @if ($message->sender && $message->sender->image_path)
                            <img class="avatar-img__item" src="{{ asset($message->sender->image_path) }}" alt="プロフィール画像">
                        @else
                            <div class="avatar-img__item avatar-img__item--no-image"></div>
                        @endif
                    </div>
                    <div>
                        <p class="username">{{ $message->sender->name }}</p>

                        @if (old('edit_id') == $message->id)
                            {{-- 編集フォーム --}}
                            <form action="{{ '/chat/update/' . $message->id }}" method="POST">
                                @csrf
                                <input type="hidden" name="edit_id" value="{{ $message->id }}">
                                <input type="text" name="message" value="{{ old('message', $message->message) }}">
                                <button type="submit" style="display: none;"></button> {{-- Enterキー用 --}}
                            </form>
                        @else
                            {{-- 通常表示 --}}
                            @if($message->message)
                                <div class="bubble">{{ $message->message }}</div>
                            @endif
                            @if($message->image_path)
                                <div class="bubble">
                                    <img src="{{ asset('storage/' . $message->image_path) }}" alt="送信画像" style="max-width: 200px;">
                                </div>
                            @endif

                            {{-- 編集・削除ボタン --}}
                            @if($message->sender_id === auth()->id())
                                <form action="{{ '/chat/action' }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="message_id" value="{{ $message->id }}">
                                        <button class="edit-btn" type="submit" name="action" value="edit">編集</button>
                                        <button class="delete-btn" type="submit" name="action" value="delete">削除</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="chat-group">
            <div class="error-message">
            <p>
                @error('message')
                    <span>{{ $message }}</span><br>
                @enderror
                @error('image')
                    <span>{{ $message }}</span>
                @enderror
            </p>
            <div>
            <form class="chat-form"action="{{ '/chat/send/' . $transaction->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <input class="chat-message" type="text" name="message" placeholder="取引メッセージを記入してください" />
                </div>
                <div>
                    <input class="chat-image" type="file" name="image" accept="image/*" style="display:none;" id="imageInput">
                    <label for="imageInput" class="image-btn">画像を追加</label>
                </div>
                <div>
                    <button class="send-btn" type="submit">
                        <img class="icon" src="{{ asset('storage/images/sending.jpg') }}" alt="sending_icon">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
