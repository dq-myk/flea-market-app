<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use App\Models\Sell;
use App\Models\Purchase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    //いいねした商品だけが表示される
        public function test_my_list_like()
    {
        $user = User::factory()->create();

        $likedItem1 = Item::factory()->create();
        $likedItem2 = Item::factory()->create();
        $notLikedItem = Item::factory()->create();

        $this->actingAs($user);

        $user->likes()->create(['item_id' => $likedItem1->id]);
        $user->likes()->create(['item_id' => $likedItem2->id]);

        $likedItems = $user->likes->pluck('item.name')->toArray();

        $response = $this->get('/?tab=mylist');

        $response->assertSee($likedItem1->name)
                ->assertSee($likedItem2->name)
                ->assertDontSee($notLikedItem->name);
    }

    //購入済み商品を表示
    public function test_my_list_purchase()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $user->likes()->create(['item_id' => $item->id]);

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
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

        $response = $this->get('/?tab=mylist');

        $response->assertDontSee($item->name);
        $response->assertDontSee($item->image_path);

    }

    //未承認の場合は内容非表示
    public function test_unapproved_not_display()
    {
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertDontSee('No items available');
    }
}
