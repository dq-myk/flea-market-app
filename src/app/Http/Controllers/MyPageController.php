<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Sell;

class MyPageController extends Controller
{
    //マイページで出品と購入を表示
    public function show(Request $request)
    {
        $tab = $request->query('tab', 'sell');
        $user = auth()->user();
        $items = [];

        if ($tab === 'sell') {
            $items = $user->sells()->with('item')->get()->pluck('item');
        } elseif ($tab === 'buy') {
            $items = $user->purchases()->with('item')->get()->pluck('item');
        }

        return view('profile', compact('user', 'tab', 'items'));
    }
}
