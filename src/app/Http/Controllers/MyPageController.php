<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Transaction;

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

                if ($isSeller) {
                    $item->unread_messages_count = $transaction->buyerMessages()
                    ->where('sender_id', '!=', $user->id)
                    ->where('read', false)->count();
                }
                else {
                    $item->unread_messages_count = $transaction->sellerMessages()
                    ->where('sender_id', '!=', $user->id)
                    ->where('read', false)->count();
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
                ->with(['item', 'messages'])
                ->get();

            $buyTrades = Transaction::where('buyer_id', $user->id)
                ->whereIn('status', ['in_progress'])
                ->with(['item', 'messages'])
                ->get();

            $items = collect();

            foreach ($sellTrades->merge($buyTrades) as $transaction) {
                $item = $transaction->item;
                $item->transaction = $transaction;

                $lastMessage = $transaction->messages->sortByDesc('created_at')->first();
                $item->last_message_at = $lastMessage ? $lastMessage->created_at : null;

                $items->push($item);
            }

            $unreadItemsById = $unreadItems->keyBy('id');
            foreach ($items as $item) {
                if (isset($unreadItemsById[$item->id])) {
                    $item->unread_messages_count = $unreadItemsById[$item->id]->unread_messages_count;
                } else {
                    $item->unread_messages_count = 0;
                }
            }

            $items = $items->sortByDesc(function ($item) {
                return $item->last_message_at;
            })->values();
        }

        return view('profile', compact('user', 'tab', 'items', 'sellTrades', 'buyTrades', 'totalUnread'));
    }
}
