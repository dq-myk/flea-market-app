<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProfileChangeTest extends TestCase
{
    use RefreshDatabase;

    //プロフィール変更画面初期値表示
    public function test_profile_change()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        $response->assertSee($user->image_path)
        ->assertSee($user->name)
        ->assertSee($user->post_code)
        ->assertSee($user->address)
        ->assertSee($user->building);
    }

}
