<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    // 取引とのリレーション (多対1)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // ユーザー（購入者）とのリレーション(多対1)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // ユーザー（出品者）とのリレーション(多対1)
    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    // 出品者として評価されたレビュー
    public function scopeToSeller($query, $userId)
    {
        return $query->where('reviewee_id', $userId)
                    ->whereHas('transaction', function ($q) {
                        $q->whereColumn('seller_id', 'reviewee_id');
                    });
    }

    // 購入者として評価されたレビュー
    public function scopeToBuyer($query, $userId)
    {
        return $query->where('reviewee_id', $userId)
                    ->whereHas('transaction', function ($q) {
                        $q->whereColumn('buyer_id', 'reviewee_id');
                    });
    }
}
