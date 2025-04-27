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

    // ユーザーに対して購入者としての評価
    public function buyerReviews()
    {
        return $this->hasMany(UserReview::class, 'reviewed_id')->where('reviewer_id', $this->id);
    }

    // ユーザーに対して出品者としての評価
    public function sellerReviews()
    {
        return $this->hasMany(UserReview::class, 'reviewed_id')->where('reviewer_id', $this->id);
    }
}
