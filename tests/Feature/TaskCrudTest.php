<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_list_tasks(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/tasks', [
            'title' => 'Integration Task',
            'description' => 'Feature-level test',
        ]);

        $response->assertCreated()
                 ->assertJsonPath('task.title', 'Integration Task');

        $list = $this->actingAs($user)->getJson('/tasks');

        $list->assertOk()
             ->assertJsonCount(1, 'tasks');
    }

    public function test_user_can_update_task(): void
    {
        $user = User::factory()->create();

        $task = $this->actingAs($user)->postJson('/tasks', [
            'title' => 'Old',
            'description' => 'Old desc',
        ])->json('task');

        $update = $this->actingAs($user)->putJson("/tasks/{$task['id']}", [
            'title' => 'New',
            'description' => 'New desc',
            'is_done' => true,
        ]);

        $update->assertOk()
               ->assertJsonPath('task.title', 'New')
               ->assertJsonPath('task.is_done', true);
    }

    public function test_user_can_delete_task(): void
    {
        $user = User::factory()->create();

        $task = $this->actingAs($user)->postJson('/tasks', [
            'title' => 'To delete',
            'description' => 'Desc',
        ])->json('task');

        $delete = $this->actingAs($user)->deleteJson("/tasks/{$task['id']}");

        $delete->assertOk();

        $list = $this->actingAs($user)->getJson('/tasks');
        $list->assertOk()->assertJsonCount(0, 'tasks');
    }
}
