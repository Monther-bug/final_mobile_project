<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_notifications()
    {
        $user = User::factory()->create();
        
        // Create fake notifications directly in DB since we haven't implemented a Notification class to send yet
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => ['title' => 'Welcome', 'body' => 'Hello World'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/notifications');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'data', 'read_at', 'created_at']
                    ]
                ]
            ]);
    }

    public function test_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        
        $notification = $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => ['title' => 'Test', 'body' => 'Test Body'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/notifications/{$notification->id}/read");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_mark_all_as_read()
    {
        $user = User::factory()->create();
        
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => ['title' => 'One'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => ['title' => 'Two'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->postJson('/api/notifications/read-all');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertEquals(0, $user->unreadNotifications()->count());
    }
}
