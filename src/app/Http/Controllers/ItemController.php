<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // クエリパラメータの'tab'を取得。デフォルト値は'home'
        $tab = $request->query('tab', 'home');

        if ($tab === 'mylist') {
            // My Listタブ用の処理
            $items = auth()->user()->myListItems; // ユーザーのお気に入りアイテムを取得
        } else {
            // その他のタブ用の処理
            $items = Item::all(); // 全アイテムを取得する例
        }

        return view('index', compact('tab', 'items'));
    }

    //検索機能の実装
    public function search(Request $request)
    {
        $items = Item::query()
        ->KeywordSearch($request->keyword)
        ->get();

        return view('index', compact('items'));
    }

    //商品詳細ページを表示
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id); // IDで商品を取得（存在しない場合は404エラー）

        return view('item_detail', compact('item'));
    }

    /*public function detail(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id); // 対象商品の取得

        // コメントを保存
        $item->comments()->create([
            'content' => $request->comment,
            'user_id' => auth()->id(), // ログインユーザーIDを取得
        ]);

        return redirect()->route('item.detail', $item_id);
    }*/
}
