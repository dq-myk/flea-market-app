<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
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
        $user = User::factory()->unverified()->create();
    $item = Item::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/purchase/' . $item->id . '/confirm', [
        'payment_method' => 'カード支払い',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect('/email/verify');

    $user->forceFill(['email_verified_at' => now()])->save();

    $response = $this->actingAs($user)->post('/purchase/' . $item->id . '/confirm', [
        'payment_method' => 'カード支払い',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect('/purchase/' . $item->id . '/confirm');

    $confirmationResponse = $this->get('/purchase/' . $item->id . '/confirm');
    $confirmationResponse->assertViewHas('paymentMethod', 'カード支払い');
    $confirmationResponse->assertViewHas('item', $item);
    }
}
