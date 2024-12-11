<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    //商品詳細情報取得
    public function test_view_item_detail()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $categories = Category::create([
            'content' => '家電',
            'content' => 'ゲーム',
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
