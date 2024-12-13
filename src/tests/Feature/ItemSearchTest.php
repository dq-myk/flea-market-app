<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    //商品名の部分一致検索
    public function test_item_search()
    {
        $item = Item::factory()->create(['name' => 'HDD']);

        $response = $this->get('/');
        $response->assertStatus(200);

        $results = Item::keywordSearch('HD')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('HDD', $results->first()->name);

        $item = Item::factory()->create(['name' => '腕時計']);

        $results = Item::keywordSearch('時計')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('腕時計', $results->first()->name);
    }

    //検索状態をマイリストページでも保持
    public function test_keep_search_keyword()
    {
        $item = Item::factory()->create(['name' => 'HDD']);

        $response = $this->get('/');
        $response->assertStatus(200);

        $results = Item::keywordSearch('HD')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('HDD', $results->first()->name);

        $responseMyList = $this->get('/?tab=mylist');
        $responseMyList->assertStatus(200);

        $response->assertSee('HD');

        $item = Item::factory()->create(['name' => '腕時計']);

        $response = $this->get('/');
        $response->assertStatus(200);

        $results = Item::keywordSearch('時計')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('腕時計', $results->first()->name);

        $responseMyList = $this->get('/?tab=mylist');
        $responseMyList->assertStatus(200);

        $response->assertSee('時計');
    }
}
