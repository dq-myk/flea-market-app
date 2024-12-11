<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    //配送先住所変更
    public function test_address_change()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

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
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

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

