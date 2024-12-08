<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;

class ItemDetailTest extends TestCase
{
        use RefreshDatabase;

    public function test_view_item_detail()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'image_path' => 'storage/images/lunch.jpg',
        ]);

        $this->actingAs($user);

        $categories = Category::create([
            'content' => '家電',
            'content' => 'ゲーム',
        ]);

        $item = Item::create([
            'name' => 'HDD',
            'brand' => 'Buffalo',
            'detail' => '高速で信頼性の高いハードディスク',
            'image_path' => 'storage/images/HDD+Hard+Disk.jpg',
            'price' => 5000,
            'color' => '黒',
            'condition' => '目立った傷や汚れなし',
            'status' => '新品',
            'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
        ]);

        $item->categories()->attach($categories->pluck('id')->toArray());

        $comment = Comment::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => '大容量で動画もたくさん保存出来て良いです'
        ]);

        $like = Like::create([
            'item_id' => $item->id,
            'user_id' => $user->id
        ]);

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        $response->assertSee($item->image_path)
        ->assertSee($item->name)
        ->assertSee($item->brand)
        ->assertSee(number_format($item->price))
        ->assertSee($item->likes->count())
        ->assertSee($item->comments->count())
        ->assertSee($item->color)
        ->assertSee($item->status)
        ->assertSee($item->status_comment)
        ->assertSee($item->categories->pluck('content')->implode(', '))
        ->assertSee($item->condition)
        ->assertSee($comment->user->image_path)
        ->assertSee($comment->user->name)
        ->assertSee($comment->content);
    }
}
