<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    //コメント送信
    public function test_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $commentData = [
            'content' => 'これはテストコメントです。',
        ];

        $response = $this->post("/item/{$item->id}/comment", $commentData);

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
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/item/{$item->id}/comment", [
            'content' => str_repeat('a', 256)
        ]);

        $response->assertSessionHasErrors(['content' => 'コメント内容は255文字以内で入力してください']);
    }

    //2未入力でコメント送信
    public function test_comment_not_entered()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/item/{$item->id}/comment", [
            'content' => ''
        ]);

        $response->assertSessionHasErrors(['content' => 'コメントを入力してください']);
    }
}
