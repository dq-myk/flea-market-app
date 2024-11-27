<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Like;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    //未承認、承認時の表示、検索機能保持設定
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'home');
        $keyword = $request->query('keyword');

        $itemsQuery = Item::query()->keywordSearch($keyword);

        if ($tab === 'mylist') {
            if (auth()->check()) {
                $itemsQuery->whereHas('likes', function ($query) {
                    $query->where('user_id', auth()->id());
                });
            } else {
                $itemsQuery->whereRaw('1 = 0');
            }
        } else {
            $itemsQuery->whereDoesntHave('sells');
        }

        $items = $itemsQuery->get();

        return view('index', compact('tab', 'items', 'keyword'));
    }

        //商品詳細ページを表示
        public function show($id)
    {
        $item = Item::withCount(['comments', 'likes'])->findOrFail($id);

        $user = auth()->check() ? auth()->user() : null;
        $isLiked = $user ? $item->likes()->where('user_id', $user->id)->exists() : false;

        return view('item_detail', [
            'item' => $item,
            'item_id' => $id,
            'user' => $user,
            'commentsCount' => $item->comments_count,
            'likesCount' => $item->likes_count,
            'isLiked' => $isLiked
        ]);
    }

        //いいね処理
        public function like(Item $item)
    {
        $user = auth()->user();

        $existingLike = Like::where('user_id', $user->id)->where('item_id', $item->id)->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        $item->update(['likes_count' => $item->likes()->count()]);

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
