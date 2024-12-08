<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeTest extends TestCase
{
    public function test_Like()
    {
        // 実行: ユーザーとしてログインし、アイテムに対していいねを付ける
        $this->actingAs($user)
            ->post("/item/{$item->id}/like");

        // 検証: いいねが作成されていることを確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // いいね数が正しく更新されたことを確認
        $this->assertEquals(1, $item->fresh()->likes_count);
    }

    public function test_unlike()
    {
        // 実行: ユーザーとしてログインし、アイテムに対していいねを付ける
        $this->actingAs($user)
            ->post("/item/{$item->id}/like");

        // ユーザーがアイテムにいいねする
        $this->user->likes()->attach($this->item->id);

        // 検証: いいねが削除されていることを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // いいね数が正しく更新されたことを確認
        $this->assertEquals(0, $item->fresh()->likes_count);
    }
}
