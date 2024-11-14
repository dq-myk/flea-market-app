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

    // マイリストとのリレーション (1対多)
    public function myLists()
    {
        return $this->hasMany(MyList::class);
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

        public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', '%' . $keyword . '%');
            });
        }

        return $query;
    }

}

