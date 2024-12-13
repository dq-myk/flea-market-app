<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    //いいね処理
    public function test_like()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/like");

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $item->refresh();
        $this->assertEquals($item->likes()->count(), 1);
    }

    //いいねアイコンの色を変更
    public function test_like_icon()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $this->post("/item/{$item->id}/like");

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->refresh();
        $this->assertEquals($item->likes()->count(), 1);

        $expectedIconPath = 'storage/images/星アイコン黄色.png';
        $actualIconPath = $item->likeIcon();
        $this->assertEquals($expectedIconPath, $actualIconPath);
    }

    //いいね処理解除
    public function test_delete_like()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

            $response = $this->get("/item/{$item->id}");
            $response->assertStatus(200);

            $this->post("/item/{$item->id}/like");

            $this->assertDatabaseHas('likes', [
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);

            $response = $this->post("/item/{$item->id}/like");

            $this->assertDatabaseMissing('likes', [
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
            $item->refresh();
            $this->assertEquals($item->likes()->count(), 0);
    }
}