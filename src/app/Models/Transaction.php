<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    // 商品とのリレーション (多対1)
    public function item() {
        return $this->belongsTo(Item::class);
    }

    // 購入履歴とのリレーション (多対1)
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
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

    // 出品者から届いた未読メッセージ
    public function sellerMessages()
    {
        return $this->hasMany(Message::class, 'transaction_id')->where('sender_id', $this->seller_id);
    }

    // 購入者から届いた未読メッセージ
    public function buyerMessages()
    {
        return $this->hasMany(Message::class, 'transaction_id')->where('sender_id', $this->buyer_id);
    }

    // 評価とのリレーション（1対多）
    public function userReviews()
    {
        return $this->hasMany(UserReview::class);
    }
}
