<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // 商品とのリレーション (多対1)
    public function item() {
        return $this->belongsTo(Item::class);
    }

    // 購入者とのリレーション (多対1)
    public function buyer() {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // 出品者とのリレーション (多対1)
    public function seller() {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // チャットメッセージとのリレーション (多対1)
    public function messages() {
        return $this->hasMany(Message::class);
    }
}
