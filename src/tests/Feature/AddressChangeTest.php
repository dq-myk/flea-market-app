<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class AddressChangeTest extends TestCase
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

    //配送先住所変更
    public function test_address_change()
    {
        $user = $this->test_create_user();
        $item = $this->test_create_item();

        $new_address = [
            'post_code' => '234-567',
            'address' => '東京都武蔵村山市1-2-3',
            'building' => '村山ハイツ303',
        ];

        $response = $this->withoutMiddleware()->post('/purchase/address/' . $item->id, $new_address);

        $response->assertStatus(302);
        $response->assertRedirect('/purchase/' . $item->id);

        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertSeeText($new_address['post_code']);
        $response->assertSeeText($new_address['address']);
        $response->assertSeeText($new_address['building']);
    }

    //配送先住所変更後の購入処理
    public function test_address_change_purchase()
    {
        $user = $this->test_create_user();
        $item = $this->test_create_item();

        $new_address = [
            'post_code' => '234-567',
            'address' => '東京都武蔵村山市1-2-3',
            'building' => '村山ハイツ303',
        ];

        $response = $this->withoutMiddleware()->post('/purchase/address/' . $item->id, $new_address);

        $response->assertStatus(302);
        $response->assertRedirect('/purchase/' . $item->id);

        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertSeeText($new_address['post_code']);
        $response->assertSeeText($new_address['address']);
        $response->assertSeeText($new_address['building']);

        $user->purchases()->create(['item_id' => $item->id]);

        $response = $this->withoutMiddleware()->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'card',
            'item_id' => $item->id,
        ];

        $response = $this->post("/purchase/{item_id}/complete", $purchaseData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}

