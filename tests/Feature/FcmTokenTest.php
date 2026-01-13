<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FcmTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_fcm_token()
    {
        $user = User::factory()->create();
        $token = 'test-fcm-token-123';

        $response = $this->actingAs($user)
            ->postJson('/api/update-fcm-token', [
                'token' => $token,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'FCM token updated successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'fcm_token' => $token,
        ]);
    }

    public function test_unauthenticated_user_cannot_update_fcm_token()
    {
        $response = $this->postJson('/api/update-fcm-token', [
            'token' => 'some-token',
        ]);

        $response->assertStatus(401);
    }

    public function test_token_is_required_validation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/update-fcm-token', []);



        $response->assertStatus(422)
            ->assertJsonValidationErrors(['token']);
    }
}
