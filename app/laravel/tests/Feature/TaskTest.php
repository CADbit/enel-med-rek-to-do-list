<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new task.
     */
    public function test_can_create_task(): void
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => TaskStatus::NEW->value,
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'status',
                'created_at',
                'updated_at',
            ])
            ->assertJson([
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'status' => $taskData['status'],
            ]);

        $this->assertDatabaseHas('tasks', $taskData);
    }

    /**
     * Test getting all tasks.
     */
    public function test_can_get_all_tasks(): void
    {
        // Create some test tasks
        $tasks = Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'data' => [
                        [
                            'id' => $tasks[0]->id,
                            'title' => $tasks[0]->title,
                            'description' => $tasks[0]->description,
                            'status' => $tasks[0]->status->value,
                            'status_color' => $tasks[0]->status->color(),
                        ],
                    ],
                    'current_page' => 1,
                    'per_page' => 10,
                    'total' => 3,
                ],
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 10,
                    'total' => 3,
                ],
            ])
            ->assertJsonCount(3, 'data.data');
    }

    /**
     * Test getting a single task.
     */
    public function test_can_get_single_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'status',
                'created_at',
                'updated_at',
            ])
            ->assertJson([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status->value,
            ]);
    }

    /**
     * Test updating a task.
     */
    public function test_can_update_task(): void
    {
        $task = Task::factory()->create();
        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => TaskStatus::COMPLETED->value,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'status',
                'created_at',
                'updated_at',
            ])
            ->assertJson([
                'title' => $updateData['title'],
                'description' => $updateData['description'],
                'status' => $updateData['status'],
            ]);

        $this->assertDatabaseHas('tasks', $updateData);
    }

    /**
     * Test deleting a task.
     */
    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * Test validation when creating a task.
     */
    public function test_validates_required_fields_when_creating_task(): void
    {
        $response = $this->postJson('/api/tasks', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test validation when updating a task.
     */
    public function test_validates_fields_when_updating_task(): void
    {
        $task = Task::factory()->create();
        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => '',
            'status' => 'invalid_status',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'status']);
    }
}
