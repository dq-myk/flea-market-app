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

    // Userリレーション（多対多の中間テーブルとして）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Itemリレーション（多対多の中間テーブルとして）
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
