<?php

namespace App\Application\UseCases;

use App\Domain\Interfaces\TaskRepositoryInterface;

class UpdateTask
{
    public function __construct(
        private readonly TaskRepositoryInterface $repository
    ) {}

    public function execute(int $taskId, string $title, string $description, bool $isDone, int $userId)
    {
        $task = $this->repository->findById($taskId);

        if (!$task || $task->userId !== $userId) {
            throw new \Exception('Task not found or forbidden');
        }

        $task->title = $title;
        $task->description = $description;
        $task->isDone = $isDone;

        return $this->repository->save($task);
    }
}
