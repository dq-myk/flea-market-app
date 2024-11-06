<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    // Itemリレーション（1対多）
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
