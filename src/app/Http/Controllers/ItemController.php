<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Like;
use App\Http\Requests\CommentRequest;

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

    public function show($id)
    {
        // 商品情報を取得し、コメント数といいね数も含める
        $item = Item::withCount(['comments', 'likes'])->findOrFail($id);

        // ログインユーザーがその商品をいいねしているかを判定
        $user = auth()->user();
        $isLiked = $user && $item->likes()->where('user_id', $user->id)->exists();

        // 必要なデータをビューに渡す
        return view('item_detail', [
            'item' => $item,
            'commentsCount' => $item->comments_count, // コメント数
            'likesCount' => $item->likes_count, // いいね数
            'isLiked' => $isLiked // ユーザーがいいねしているかどうか
        ]);
    }

        public function like(Item $item)
    {
        $user = auth()->user();

        // すでにこの商品をいいねしているかチェック
        $existingLike = Like::where('user_id', $user->id)->where('item_id', $item->id)->first();

        if ($existingLike) {
            // すでにいいねしている場合は、いいねを解除する
            $existingLike->delete();
        } else {
            // まだいいねしていない場合は、新たに登録
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        // 商品のいいね数を更新
        $item->update(['likes_count' => $item->likes()->count()]);

        // 正しいURLにリダイレクト
        return redirect("/item/{$item->id}");
    }

    public function detail(CommentRequest $request, $item_id)
    {
        Comment::create([
            'item_id' => $item_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return redirect('/item/{item_id}');
    }
}
