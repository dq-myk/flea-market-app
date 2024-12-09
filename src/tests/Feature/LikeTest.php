<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    private function test_create_user(): User
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        return $user;
    }

    private function test_create_item(): Item
    {
        return Item::create([
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
    }

    //いいね処理
    public function test_like()
    {
        $user = $this->test_create_user();
        $item = $this->test_create_item();

        $user->likes()->create(['item_id' => $item->id]);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/like");

        // Assert
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
        $user = $this->test_create_user();
        $item = $this->test_create_item();

        $user->likes()->create(['item_id' => $item->id]);

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
            $user = $this->test_create_user();
            $item = $this->test_create_item();

            $response = $this->get("/item/{$item->id}");
            $response->assertStatus(200);

            $response = $this->post("/item/{$item->id}/like");

            $this->assertDatabaseMissing('likes', [
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
            $item->refresh();
            $this->assertEquals($item->likes()->count(), 0);
    }
}
