<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
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

    // 出品とのリレーション（1対1）
    public function sell()
    {
        return $this->belongsTo(Sell::class);
    }

    // 取引チャットとのリレーション（1対多）
    public function transactionChats()
    {
        return $this->hasMany(TransactionChat::class);
    }

    // 取引とのリレーション（1対多）
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
