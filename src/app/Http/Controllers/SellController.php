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
    //出品画面表示
    public function show(Request $request)
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    //出品処理
    public function store(ExhibitionRequest $request)
    {
        $imagePath = null;
        if ($request->hasFile('item_image')) {
            $image = $request->file('item_image');
            $imagePath = $image->store('public/images');
            $imagePath = str_replace('public/', 'storage/', $imagePath);
        }

        $item = Item::create([
            'name' => $request->item_name,
            'brand' => $request->brand,
            'detail' => $request->item_detail,
            'price' => $request->price,
            'color' => $request->color,
            'condition' => $request->condition,
            'status' => $request->status,
            'status_comment' => $request->status_comment,
            'image_path' => $imagePath,
            'user_id' => Auth::id(),
        ]);

        $item->categories()->sync($request->category_ids);

        Sell::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'price' => $item->price,
        ]);

        return redirect('/');
    }
}
