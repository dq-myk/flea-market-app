<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    // Userリレーション（多対1）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Itemリレーション（多対1）
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
