<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class ItemSearchTest extends TestCase
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
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }

    //商品名の部分一致検索
    public function test_item_search()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $results = Item::keywordSearch('HD')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('HDD', $results->first()->name);

        $results = Item::keywordSearch('時計')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('腕時計', $results->first()->name);
    }

    //検索状態をマイリストでも保持
    public function test_keep_search_keyword()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $results = Item::keywordSearch('HD')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('HDD', $results->first()->name);

        $results = Item::keywordSearch('時計')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('腕時計', $results->first()->name);

        $responseMyList = $this->get('/?tab=mylist');
        $responseMyList->assertStatus(200);

        $response->assertSee('HD');
        $response->assertSee('時計');
    }
}
