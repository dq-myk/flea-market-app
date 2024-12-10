<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Sell;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // テスト用データを作成
        $items = [
            [
                'name' => '腕時計',
                'brand' => 'Armani',
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'storage/images/Armani+Mens+Clock.jpg',
                'price' => 15000,
                'color' => '文字盤黒',
                'condition' => '良好',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。傷もありません。',
            ],
            [
                'name' => 'HDD',
                'brand' => 'Buffalo',
                'detail' => '高速で信頼性の高いハードディスク',
                'image_path' => 'storage/images/HDD+Hard+Disk.jpg',
                'price' => 5000,
                'color' => '黒',
                'condition' => '目立った傷や汚れなし',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
            ],
            [
                'name' => '玉ねぎ3束',
                'brand' => '宮崎県産',
                'detail' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'storage/images/iLoveIMG+d.jpg',
                'price' => 300,
                'condition' => 'やや傷や汚れあり',
                'status' => '新品',
                'status_comment' => '商品の状態はやや良好です。少しの傷や汚れがあります。',
            ],
            [
                'name' => '革靴',
                'brand' => 'AEON',
                'detail' => 'クラシックなデザインの革靴',
                'image_path' => 'storage/images/Leather+Shoes+Product+Photo.jpg',
                'price' => 4000,
                'color' => '黒',
                'condition' => '状態が悪い',
                'status' => '中古品',
                'status_comment' => '商品の状態は悪いです。傷や汚れがあります。',
            ],
            [
                'name' => 'ノートPC',
                'brand' => 'DELL',
                'detail' => '高性能なノートパソコン',
                'image_path' => 'storage/images/Living+Room+Laptop.jpg',
                'price' => 45000,
                'color' => 'シルバー',
                'condition' => '良好',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。傷もありません。',
            ],
            [
                'name' => 'マイク',
                'brand' => 'MAXIM',
                'detail' => '高音質のレコーディング用マイク',
                'image_path' => 'storage/images/Music+Mic+4632231.jpg',
                'price' => 8000,
                'color' => '黒',
                'condition' => '目立った傷や汚れなし',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => 'AEON',
                'detail' => 'おしゃれなショルダーバッグ',
                'image_path' => 'storage/images/Purse+fashion+pocket.jpg',
                'price' => 3500,
                'color' => '赤',
                'condition' => 'やや傷や汚れあり',
                'status' => '中古品',
                'status_comment' => '商品の状態はやや良好です。少しの傷や汚れがあります。',
            ],
            [
                'name' => 'タンブラー',
                'brand' => 'ニトリ',
                'detail' => '使いやすいタンブラー',
                'image_path' => 'storage/images/Tumbler+souvenir.jpg',
                'price' => 500,
                'color' => '黒',
                'condition' => '状態が悪い',
                'status' => '中古品',
                'status_comment' => '商品の状態は悪いです。傷や汚れがあります。',

            ],
            [
                'name' => 'コーヒーミル',
                'brand' => 'カリタ',
                'detail' => '手動のコーヒーミル',
                'image_path' => 'storage/images/Waitress+with+Coffee+Grinder.jpg',
                'price' => 4000,
                'color' => 'ブラウン',
                'condition' => '良好',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。傷もありません。',
            ],
            [
                'name' => 'メイクセット',
                'brand' => 'DHC',
                'detail' => '便利なメイクアップセット',
                'image_path' => 'storage/images/外出メイクアップセット.jpg',
                'price' => 2500,
                'condition' => '目立った傷や汚れなし',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }

    //商品一覧ページ表示テスト
    public function test_item_list_access()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertViewHas('items', function ($items) {
        return count($items) === 10;
        });

        $items = $response->viewData('items');
        foreach ($items as $index => $item) {
            $response->assertSee($item['name'])
                    ->assertSee($item['image_path']);
        }
    }

    //購入済み商品を表示
    public function test_purchased_item()
    {
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

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertSee($item->image_path);
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

        $response = $this->get('/');
        $response->assertSee($item->image_path);
        $itemName = utf8_decode($item->name);
        $this->assertDatabaseMissing('items', ['name' => $itemName]);
        $response->assertDontSee($itemName);
    }
}
