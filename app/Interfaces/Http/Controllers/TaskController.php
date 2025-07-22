<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\UseCases\ListTasks;
use App\Application\UseCases\UpdateTask;
use App\Application\UseCases\DeleteTask;
use App\Application\UseCases\CreateTask;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(
        private readonly CreateTask $createTask,
        private readonly ListTasks $listTasks,
        private readonly UpdateTask $updateTask,
        private readonly DeleteTask $deleteTask,
    ) {}

    public function index(Request $request): JsonResponse
    {
    $userId = $request->user()->id;
    $tasks = $this->listTasks->execute($userId);

    return response()->json([
        'tasks' => array_map(fn($task) => [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'is_done' => $task->isDone,
            'created_at' => $task->createdAt->format('Y-m-d H:i:s'),
        ], $tasks)
    ]);
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $userId = $request->user()->id;

        $task = $this->createTask->execute(
            $validated['title'],
            $validated['description'],
            $userId
        );

        return response()->json([
            'message' => 'Task created',
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'is_done' => $task->isDone,
                'created_at' => $task->createdAt->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'is_done' => ['required', 'boolean'],
        ]);

        $task = $this->updateTask->execute(
            $id,
            $validated['title'],
            $validated['description'],
            $validated['is_done'],
            $request->user()->id
        );

        return response()->json([
            'message' => 'Task updated',
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'is_done' => $task->isDone,
                'created_at' => $task->createdAt->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->deleteTask->execute($id, $request->user()->id);

        return response()->json(['message' => 'Task deleted']);
    }

}
