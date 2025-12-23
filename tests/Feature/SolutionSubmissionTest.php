<?php

namespace Tests\Feature;

use App\Models\Problem;
use App\Models\User;
use App\Models\Solution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class SolutionSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_solution()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $problem = Problem::factory()->create();

        $response = $this->postJson('/api/solutions', [
            'problem_id' => $problem->id,
            'code' => 'print("Hello")',
            'time_taken' => 10,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('solutions', [
            'user_id' => $user->id,
            'problem_id' => $problem->id,
        ]);
    }

    public function test_user_cannot_update_others_solution()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $solution = Solution::create([
            'user_id' => $user1->id,
            'problem_id' => Problem::factory()->create()->id,
            'code' => 'initial code',
            'status' => 'pending',
        ]);

        Sanctum::actingAs($user2);

        $response = $this->putJson("/api/solutions/{$solution->id}", [
            'code' => 'hacked code',
        ]);

        $response->assertStatus(403);
    }
}
