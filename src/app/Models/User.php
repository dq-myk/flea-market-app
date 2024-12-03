<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notfications\CustomVerifyMail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'post_code',
        'address',
        'building',
        'image_path',
        'first_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // マイリストとのリレーション (1対多)
    public function myLists()
    {
        return $this->hasMany(MyList::class);
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

    // いいね情報とのリレーション (1対多)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }


}
