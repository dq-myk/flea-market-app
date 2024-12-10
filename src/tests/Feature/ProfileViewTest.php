<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ProfileViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_view()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'image_path' => 'storage/images/lunch.jpg',
        ]);

        $this->actingAs($user);

        $purchaseItem = Item::create([
            'name' => 'ノートPC',
            'brand' => 'DELL',
            'detail' => '高性能なノートパソコン',
            'image_path' => 'storage/images/Living+Room+Laptop.jpg',
            'price' => 45000,
            'color' => 'シルバー',
            'condition' => '良好',
            'status' => '新品',
            'status_comment' => '商品の状態は良好です。傷もありません。',
        ]);

        $sellItem = Item::create([
            'name' => 'ワイヤレスマウス',
            'brand' => 'Logitech',
            'detail' => '無線のトラックボールマウスです',
            'image_path' => 'storage/images/ワイヤレスマウス.jpg',
            'price' => 2000,
            'color' => '黒',
            'condition' => '目立った傷や汚れなし',
            'status' => '中古品',
            'status_comment' => '使用済みですが、ほぼ新品の状態です',
            'user_id' => $user->id
        ]);

        // マイページにアクセス
        $response = $this->withoutMiddleware()->get('/mypage');

        $response->assertSee($user->image_path);
        $response->assertSee($user->name);

        $user->purchases()->create(['item_id' => $purchaseItem->id]);

        $user->sells()->create([
            'item_id' => $sellItem->id,
            'price' => 2000,
        ]);

        $response = $this->actingAs($user)->get('/mypage?tab=buy');
        $response->assertStatus(200);
        $response->assertSee($purchaseItem->image_path);
        $response->assertSee($purchaseItem->name);

        $response = $this->actingAs($user)->get('/mypage?tab=sell');
        $response->assertStatus(200);
        $response->assertSee($sellItem->image_path);
        $response->assertSee($sellItem->name);
    }
}
