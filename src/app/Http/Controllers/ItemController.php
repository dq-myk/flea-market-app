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
        $tab = $request->query('tab', 'home');

        // $tabが'mylist'の場合
        if ($tab === 'mylist') {
            if (!auth()->check() || !auth()->user()) {
                $items = collect();
            } else {
                $items = auth()->user()->likes()->with('item')->get()->pluck('item');
            }
        } else {
            $items = Item::whereDoesntHave('sells')->get();
        }

        return view('index', compact('tab', 'items'));
    }

    //検索機能の実装
    public function search(Request $request)
    {
        $items = Item::query()
            ->KeywordSearch($request->keyword)
            ->get();

        $tab = $request->query('tab', 'home');

        return view('index', compact('items', 'tab'));
    }

        //商品詳細ページを表示
        public function show($id)
    {
        // コメント数といいね数も含めて商品情報を取得
        $item = Item::withCount(['comments', 'likes'])->findOrFail($id);

        $user = auth()->check() ? auth()->user() : null;
        $isLiked = $user ? $item->likes()->where('user_id', $user->id)->exists() : false;

        return view('item_detail', [
            'item' => $item,
            'item_id' => $id,
            'user' => $user,
            'commentsCount' => $item->comments_count,
            'likesCount' => $item->likes_count,
            'isLiked' => $isLiked // ユーザーがいいねしているかどうか
        ]);
    }

        //いいね処理
        public function like(Item $item)
    {
        $user = auth()->user();

        // すでにこの商品をいいねしているかチェック
        $existingLike = Like::where('user_id', $user->id)->where('item_id', $item->id)->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        $item->update(['likes_count' => $item->likes()->count()]); // 商品のいいね数を更新

        return redirect("/item/{$item->id}");
    }

        //コメント投稿
        public function comment(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        Comment::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
        ]);

        return redirect("/item/{$item_id}");
    }
}
