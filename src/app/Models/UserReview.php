<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    use HasFactory;

    // 購入履歴とのリレーション (多対1)
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
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
}
