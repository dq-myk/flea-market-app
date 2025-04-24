<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    // カテゴリとのリレーション (多対多)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category');
    }

    // いいねとのリレーション (1対多)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // コメントとのリレーション (1対多)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 購入履歴とのリレーション (1対多)
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // 出品情報とのリレーション (1対多)
    public function sells()
    {
        return $this->hasMany(Sell::class);
    }

    // 取引とのリレーション（在庫が1つなので1対1）
    public function transaction() {
        return $this->hasOne(Transaction::class);
    }

        public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', '%' . $keyword . '%');
            });
        }

        return $query;
    }

    public function likeIcon()
    {
        return $this->likes()->exists()
            ? 'storage/images/星アイコン黄色.png'
            : 'storage/images/星アイコン.png';
    }
}

