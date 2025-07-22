<?php

namespace App\Application\UseCases;

use App\Domain\Interfaces\TaskRepositoryInterface;

class DeleteTask
{
    public function __construct(
        private readonly TaskRepositoryInterface $repository
    ) {}

    public function execute(int $taskId, int $userId): void
    {
        $task = $this->repository->findById($taskId);

        if (!$task || $task->userId !== $userId) {
            throw new \Exception('Task not found or forbidden');
        }

        $this->repository->delete($taskId);
    }
}
