<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;


class ExhibitRegistrationTest extends TestCase
{
    use RefreshDatabase;

    //出品商品情報登録
    public function test_sell()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/sell');
        $response->assertStatus(200);

        $image = UploadedFile::fake()->create('Waitress+with+Coffee+Grinder.jpg', 500, 'image/jpeg');

        $sellData = [
            'image_path' => $image,
            'category_id' => 1,
            'brand' => 'Test Brand',
            'color' => 'Red',
            'status' => '新品',
            'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
            'condition' => "良好",
            'name' => 'Test Item',
            'detail' => 'Test Detail',
            'price' => 1000,
        ];

        $response = $this->post("/sell", $sellData);

        $item = Item::where('name', 'Test Item')->first();

        if ($item) {
        $this->assertDatabaseHas('sells', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'price' => $sellData['price'],
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'Test Item',
            'brand' => 'Test Brand',
            'detail' => 'Test Detail',
            'price' => 1000,
            'color' => 'Red',
            'condition' => "良好",
            'status' => '新品',
            'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
        ]);

            $this->assertTrue($user->items()->where('item_id', $item->id)->exists());
            $this->assertTrue($item->categories->contains(1));
        }
    }
}

