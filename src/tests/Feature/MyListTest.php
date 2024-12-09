<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use App\Models\Purchase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    //いいねした商品だけが表示される
        public function test_my_list_like()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        $likedItem1 = Item::create([
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

        $likedItem2 = Item::create([
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

        $notLikedItem = Item::create([
            'name' => 'ショルダーバッグ',
            'brand' => 'AEON',
            'detail' => 'おしゃれなショルダーバッグ',
            'image_path' => 'storage/images/Purse+fashion+pocket.jpg',
            'price' => 3500,
            'color' => '赤',
            'condition' => 'やや傷や汚れあり',
            'status' => '中古品',
            'status_comment' => '商品の状態はやや良好です。少しの傷や汚れがあります。',
        ]);

        $user->likes()->create(['item_id' => $likedItem1->id]);
        $user->likes()->create(['item_id' => $likedItem2->id]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee($likedItem1->name)
                ->assertSee($likedItem2->name)
                ->assertDontSee($notLikedItem->name);
    }

    //購入済み商品を表示
    public function test_my_list_purchase()
    {
        // ユーザーを作成してログイン
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        $item = Item::create([
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

        $user->likes()->create(['item_id' => $item->id]);

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSeeText('Sold');
    }

    //出品した商品が一覧ページに表示されない
    public function test_sell_not_displayed()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        $item = Item::create([
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

        $response = $this->get('/?tab=mylist');

        $itemName = utf8_decode($item->name);
        $this->assertDatabaseMissing('items', ['name' => $itemName]);

        $response->assertDontSee($itemName);
    }

    //未承認の場合は内容非表示
    public function test_unapproved_not_display()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertDontSee('No items available');
    }
}
