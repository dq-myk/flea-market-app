<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Sell;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    //商品一覧ページ表示テスト
    public function test_item_list_access()
    {
        Item::factory()->count(10)->create();

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
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

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
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'name' => 'Test Item',
            'image_path' => 'storage/images/Waitress+with+Coffee+Grinder.jpg',
            'price' => 2000,
        ]);

        $this->actingAs($user);

        Sell::create([
        'user_id' => $user->id,
        'item_id' => $item->id,
        'price' => $item->price,
    ]);

        $response = $this->get('/');

        $response->assertDontSee($item->name);
        $response->assertDontSee($item->image_path);
    }
}
