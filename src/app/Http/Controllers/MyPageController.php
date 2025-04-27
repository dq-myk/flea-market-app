<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Sell;
use App\Models\UserReview;

class MyPageController extends Controller
{
    //マイページで出品と購入と取引中を表示
    public function show(Request $request)
    {
        $tab = $request->query('tab', 'sell');
        $user = auth()->user();
        $items = [];
        $sellTrades = collect();
        $buyTrades = collect();
        $totalUnread = 0;

        $unreadItems = Item::whereHas('transaction', function ($query) use ($user) {
                $query->where('seller_id', $user->id)
                    ->orWhere('buyer_id', $user->id);
            })
            ->with('transaction')
            ->get();

            foreach ($unreadItems as $item) {
                $transaction = $item->transaction;
                $isSeller = $transaction->seller_id === $user->id;

                // 出品者は購入者からの未読メッセージ数を取得
                if ($isSeller) {
                    $item->unread_messages_count = $transaction->buyerMessages()->where('sender_id', '!=', $user->id)->where('read', false)->count();
                }
                // 購入者は出品者からの未読メッセージ数を取得
                else {
                    $item->unread_messages_count = $transaction->sellerMessages()->where('sender_id', '!=', $user->id)->where('read', false)->count();
                }
            }

        $totalUnread = $unreadItems->sum('unread_messages_count');

        if ($tab === 'sell') {
            $items = $user->sells()->with('item')->get()->pluck('item');
        } elseif ($tab === 'buy') {
            $items = $user->purchases()->with('item')->get()->pluck('item');
        } elseif ($tab === 'trade') {
            $sellTrades = Transaction::where('seller_id', $user->id)
                ->whereIn('status', ['in_progress'])
                ->with('item')
                ->get()
                ->pluck('item');

            $buyTrades = Transaction::where('buyer_id', $user->id)
                ->whereIn('status', ['in_progress'])
                ->with('item')
                ->get()
                ->pluck('item');

            $items = $sellTrades->merge($buyTrades);
            }

        $unreadItemsById = $unreadItems->keyBy('id');

        foreach ($items as $item) {
            if (isset($unreadItemsById[$item->id])) {
                $item->unread_messages_count = $unreadItemsById[$item->id]->unread_messages_count;
            } else {
                $item->unread_messages_count = 0;
            }
        }

        return view('profile', compact('user', 'tab', 'items', 'sellTrades', 'buyTrades', 'totalUnread'));
    }

    //出品者用チャット画面表示
    public function seller(Request $request, Transaction $transaction)
    {
        $user = auth()->user();

        $transaction->load('buyer', 'item');

        if ($transaction->seller_id === $user->id) {
            $transaction->buyerMessages()
                ->where('sender_id', '!=', $user->id)
                ->where('read', false)
                ->update(['read' => true]);
        }

        $sellerId = $transaction->seller_id;

        $tradingItems = Transaction::where('seller_id', $sellerId)
            ->where('id', '!=', $transaction->id)
            ->with(['item', 'messages' => function ($query)  use ($transaction) {
                $query->where('sender_id', $transaction->buyer_id)
                    ->latest('created_at');
            }])
            ->get()
            ->sortByDesc(function ($item) {
                return optional($item->messages->first())->created_at;
            });

        $alreadyReviewed = UserReview::where('transaction_id', $transaction->id)
            ->where('reviewer_id', $transaction->buyer_id)
            ->exists();

        $openModal = $alreadyReviewed;

        if ($request->has('message')) {
            session(['chat_message' => $request->input('message')]);
        }
        $chatMessage = session('chat_message', '');

        return view('chat_seller', compact('transaction', 'tradingItems', 'openModal', 'chatMessage'));
    }

    //購入者用チャット画面表示
    public function buyer(Transaction $transaction)
    {
        $user = auth()->user();

        $transaction->load('seller', 'item');

        if ($transaction->buyer_id === $user->id) {
            $transaction->sellerMessages()
                ->where('sender_id', '!=', $user->id)
                ->where('read', false)
                ->update(['read' => true]);
        }

        return view('chat_buyer', compact('transaction'));
    }
}
