<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    //選択した支払方法を即時反映
    public function test_payment_method()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        if (!$user->hasVerifiedEmail()) {
            $response = $this->actingAs($user)->post('/purchase/1/confirm', [
                'payment_method' => 'カード支払い',
            ]);
            $response->assertStatus(302);
            $response->assertRedirect('/email/verify');
            return;
        }

        $this->actingAs($user);

        $item = Item::create([
            'name' => 'ノートPC',
            'brand' => 'DELL',
            'detail' => '高性能なノートパソコン',
            'image_path' => 'storage/images/Living+Room+Laptop.jpg',
            'price' => 45000,
            'color' => 'シルバー',
            'condition' => '良好',
            'status' => '新品',
            'status_comment' => '商品の状態は良好です。傷もありません。',
        ]);

        $paymentMethod = 'カード支払い';

        // Act
        $response = $this->actingAs($user)
            ->post('/purchase/' . $item->id . '/confirm', [
                'payment_method' => $paymentMethod,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/purchase/' . $item->id . '/confirm');

        $confirmationResponse = $this->get('/purchase/' . $item->id . '/confirm');
        $confirmationResponse->assertViewHas('paymentMethod', $paymentMethod);
        $confirmationResponse->assertViewHas('item', $item);
    }
}
