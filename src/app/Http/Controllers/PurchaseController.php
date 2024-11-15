<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        $item = Item::find(1);
        $user = auth()->user();

        return view('purchase', compact('item', 'user'));
    }
}