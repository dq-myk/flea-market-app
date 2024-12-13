<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ProfileViewTest extends TestCase
{
    use RefreshDatabase;

    //ユーザー情報取得
    public function test_profile_view()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $purchaseItem = Item::factory()->create();

        $sellItem = Item::factory()->create();

        $response = $this->get('/mypage');

        $response->assertSee($user->image_path);
        $response->assertSee($user->name);

        $user->purchases()->create(['item_id' => $purchaseItem->id]);

        $user->sells()->create([
            'item_id' => $sellItem->id,
            'price' => 2000,
        ]);

        $response = $this->get('/mypage?tab=buy');
        $response->assertStatus(200);
        $response->assertSee($purchaseItem->image_path);
        $response->assertSee($purchaseItem->name);

        $response = $this->get('/mypage?tab=sell');
        $response->assertStatus(200);
        $response->assertSee($sellItem->image_path);
        $response->assertSee($sellItem->name);
    }
}
