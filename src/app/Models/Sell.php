<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    // ユーザーとのリレーション (多対1)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 商品とのリレーション (多対1)
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // 購入履歴とのリレーション（1対1）
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
