<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    /** @var array<int,Task> */
    private array $storage = [];

    private int $autoIncrement = 1;

    public function findById(int $id): ?Task
    {
        return $this->storage[$id] ?? null;
    }

    public function findAllByUserId(int $userId): array
    {
        return array_values(array_filter(
            $this->storage,
            fn(Task $t) => $t->userId === $userId
        ));
    }

    public function save(Task $task): Task
    {
        // если новая задача — присвоим ID
        if ($task->id === null) {
            $id = $this->autoIncrement++;
            $task = new Task(
                id: $id,
                title: $task->title,
                description: $task->description,
                isDone: $task->isDone,
                userId: $task->userId,
                createdAt: $task->createdAt
            );
        }

        $this->storage[$task->id] = $task;
        return $task;
    }

    public function delete(int $id): void
    {
        unset($this->storage[$id]);
    }
}
