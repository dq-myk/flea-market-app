<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ItemPurchaseTest extends TestCase
{
    use RefreshDatabase;

    //商品購入処理
    public function test_purchase()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $user->purchases()->create(['item_id' => $item->id]);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'card',
            'item_id' => $item->id,
        ];

        $response = $this->post("/purchase/{item_id}/complete", $purchaseData);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    //購入済商品Sold表示
    public function test_purchase_sold()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $user->purchases()->create(['item_id' => $item->id]);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'card',
            'item_id' => $item->id,
        ];

        $response = $this->post("/purchase/{item_id}/complete", $purchaseData);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/');
        $response->assertSee($item->image_path);
        $response->assertSee($item->name);
        $response->assertSeeText('Sold');
    }

    //購入済商品プロフィール画面表示
    public function test_purchase_profile()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $user->purchases()->create(['item_id' => $item->id]);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'card',
            'item_id' => $item->id,
        ];

        $response = $this->post("/purchase/{item_id}/complete", $purchaseData);

        $response = $this->get('/mypage?tab=buy');
        $response->assertStatus(200);
        $response->assertSee($item->image_path);
        $response->assertSee($item->name);
    }
}