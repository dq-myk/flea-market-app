<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Sell;
use App\Http\Requests\ExhibitionRequest;

class SellController extends Controller
{
    public function show(Request $request)
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
       // 商品の画像のアップロード
        $imagePath = null;
        if ($request->hasFile('item_image')) {
            $image = $request->file('item_image');
            $imagePath = $image->store('public/images'); // ファイルを保存
            $imagePath = str_replace('public/', 'storage/', $imagePath); // パスを調整
        }

        // 出品商品を保存
        $item = Item::create([
            'name' => $request->item_name,
            'detail' => $request->item_detail,
            'price' => $request->price,
            'condition' => $request->condition,
            'image_path' => $imagePath, // 保存した画像のパス
            'user_id' => Auth::id(),
        ]);

        // 商品にカテゴリを紐づける (syncを使用)
        $item->categories()->sync($request->category_ids);

        // 出品テーブルに記録
        Sell::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'price' => $item->price,
        ]);

        // 出品が完了したら、リダイレクト
        return redirect('/');
    }
}
