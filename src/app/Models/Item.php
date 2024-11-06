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

    // MyListリレーション（中間テーブル経由でのUserとの多対多）
    public function myLists()
    {
        return $this->belongsToMany(User::class, 'my_lists', 'item_id', 'user_id')
                    ->withTimestamps();
    }

    // Commentリレーション（1対多）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Likeリレーション（1対多）
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Purchaseリレーション（1対多）
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Sellリレーション（1対多）
    public function sells()
    {
        return $this->hasMany(Sell::class);
    }

    // Categoryリレーション（多対1）
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Conditionリレーション（多対1）
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
}
