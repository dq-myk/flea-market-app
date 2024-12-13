<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    //選択した支払方法を即時反映
    public function test_payment_method()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/purchase/{$item->id}/confirm", [
            'payment_method' => 'カード支払い'
        ]);

        $response->assertRedirect("/purchase/{$item->id}");

        $response = $this->get("/purchase/{$item->id}");
        $response->assertSee('カード支払い');

        }
}
