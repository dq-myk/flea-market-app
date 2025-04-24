<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // 取引情報とのリレーション（多対1）
    public function transaction() {
    return $this->belongsTo(Transaction::class);
    }

    // ユーザーとのリレーション（多対1）
    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
