<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class My_list extends Model
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
}
