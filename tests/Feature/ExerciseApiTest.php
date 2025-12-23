<?php

namespace Tests\Feature;

use App\Models\Exercise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExerciseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_exercises()
    {
        Exercise::factory(5)->create();

        $response = $this->getJson('/api/exercises');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'category']
                ],
                'meta' // Pagination meta
            ]);
    }

    public function test_can_show_single_exercise()
    {
        $exercise = Exercise::factory()->create();

        $response = $this->getJson("/api/exercises/{$exercise->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.title', $exercise->title);
    }
}
