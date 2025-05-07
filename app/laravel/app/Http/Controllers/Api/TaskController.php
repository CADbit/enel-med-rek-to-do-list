<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Task Management API',
    description: 'API for managing tasks',
    contact: new OA\Contact(email: 'admin@example.com')
)]
class TaskController extends Controller
{
    #[OA\Get(
        path: '/api/tasks',
        summary: 'Get all tasks',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                required: false,
                description: 'Page number',
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                required: false,
                description: 'Number of tasks per page',
                schema: new OA\Schema(type: 'integer', default: 10)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of tasks',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Task')),
                        new OA\Property(property: 'current_page', type: 'integer'),
                        new OA\Property(property: 'total', type: 'integer'),
                        new OA\Property(property: 'per_page', type: 'integer'),
                        new OA\Property(property: 'last_page', type: 'integer'),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $status = $request->input('status');
        $sortField = $request->input('sort_field', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = Task::query();

        if ($status && in_array($status, array_column(TaskStatus::cases(), 'value'))) {
            $query->where('status', TaskStatus::from($status));
        }

        // Obsługa sortowania
        if ($sortField === 'status') {
            $query->orderBy('status', $sortOrder);
        } else {
            $query->orderBy($sortField, $sortOrder);
        }

        $tasks = $query->paginate($perPage);

        $data = $tasks->through(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status->value,
                'status_color' => $task->status->color(),
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => (int) $tasks->perPage(),
                'total' => $tasks->total(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/tasks',
        summary: 'Create a new task',
        tags: ['Tasks'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'New Task'),
                    new OA\Property(property: 'description', type: 'string', example: 'Task description'),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        enum: ['new', 'pending', 'waiting', 'stop', 'completed'],
                        example: 'new'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Task created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Task')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['sometimes', 'string', 'in:' . implode(',', array_column(TaskStatus::cases(), 'value'))],
        ]);

        $task = Task::create($validated);

        return response()->json($task, Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: '/api/tasks/{task}',
        summary: 'Get a specific task',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'task',
                in: 'path',
                required: true,
                description: 'Task ID',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task details',
                content: new OA\JsonContent(ref: '#/components/schemas/Task')
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found'
            ),
        ]
    )]
    public function show(Task $task)
    {
        return response()->json($task);
    }

    #[OA\Put(
        path: '/api/tasks/{task}',
        summary: 'Update a task',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'task',
                in: 'path',
                required: true,
                description: 'Task ID',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Updated Task'),
                    new OA\Property(property: 'description', type: 'string', example: 'Updated description'),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        enum: ['new', 'pending', 'waiting', 'stop', 'completed'],
                        example: 'waiting'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Task')
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
        ]
    )]
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['sometimes', 'string', 'in:' . implode(',', array_column(TaskStatus::cases(), 'value'))],
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    #[OA\Delete(
        path: '/api/tasks/{task}',
        summary: 'Delete a task',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'task',
                in: 'path',
                required: true,
                description: 'Task ID',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Task deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found'
            ),
        ]
    )]
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    #[OA\Get(
        path: '/api/tasks/statuses',
        summary: 'Get all available task statuses',
        tags: ['Tasks'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of task statuses',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'value', type: 'string', example: 'new'),
                            new OA\Property(property: 'label', type: 'string', example: 'Nowe'),
                            new OA\Property(property: 'color', type: 'string', example: 'blue'),
                        ]
                    )
                )
            ),
        ]
    )]
    public function getStatuses()
    {
        $statuses = collect(TaskStatus::cases())->map(function ($status) {
            return [
                'value' => $status->value,
                'label' => $status->label(),
                'color' => $status->color(),
            ];
        });

        return response()->json($statuses);
    }
}
