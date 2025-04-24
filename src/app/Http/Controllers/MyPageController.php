<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Sell;

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

        if ($tab === 'sell') {
            $items = $user->sells()->with('item')->get()->pluck('item');
        } elseif ($tab === 'buy') {
            $items = $user->purchases()->with('item')->get()->pluck('item');
        } elseif ($tab === 'trade') {
            $sellTrades = Transaction::where('seller_id', $user->id)
                ->where('status', 'in_progress')
                ->with('item')
                ->get()
                ->pluck('item');

            $buyTrades = Transaction::where('buyer_id', $user->id)
                ->where('status', 'in_progress')
                ->with('item')
                ->get()
                ->pluck('item');

            $items = $sellTrades->merge($buyTrades);
        }

        return view('profile', compact('user', 'tab', 'items','sellTrades', 'buyTrades'));
    }

    //出品者用チャット画面表示
    public function seller(Item $item)
    {
        return view('chat_seller', compact('item'));
    }

    //購入者用チャット画面表示
    public function buyer(Item $item)
    {
        return view('chat_buyer', compact('item'));
    }
}
