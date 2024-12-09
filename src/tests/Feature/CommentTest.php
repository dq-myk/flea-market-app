<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
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

    //コメント送信
    public function test_comment()
    {
        $user = $this->test_create_user();
        $item = $this->test_create_item();

        $commentData = [
            'content' => 'これはテストコメントです。',
        ];

        $response = $this->withoutMiddleware()->post("/item/{$item->id}/comment", $commentData);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('comments', [
            'content' => $commentData['content'],
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $this->assertEquals($item->comments()->count(), 1);
    }

    //ログイン前ユーザーコメント送信
    public function test_befoe_login_comment()
    {
        $response = $this->post('/item/{item_id}/comment', [
            'content' => 'これはテストコメントです。'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    //256文字以上でコメント送信
    public function test_max_characters()
    {
        $user = $this->test_create_user();
        $item = $this->test_create_item();

        $response = $this->withoutMiddleware()->post("/item/{$item->id}/comment", [
            'content' => str_repeat('a', 256)
        ]);

        $response->assertSessionHasErrors(['content' => 'コメント内容は255文字以内で入力してください']);
    }

    //2未入力でコメント送信
    public function test_comment_not_entered()
    {
        $user = $this->test_create_user();
        $item = $this->test_create_item();

        $response = $this->withoutMiddleware()->post("/item/{$item->id}/comment", [
            'content' => ''
        ]);

        $response->assertSessionHasErrors(['content' => 'コメントを入力してください']);
    }
}
