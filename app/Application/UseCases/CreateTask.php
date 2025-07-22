<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;
use DateTimeImmutable;
use App\Domain\Events\TaskWasCreated;
use App\Infrastructure\Events\Dispatcher;


class CreateTask
{
    public function __construct(
        private readonly TaskRepositoryInterface $repository,
        private readonly Dispatcher $dispatcher
    ) {}

    public function execute(string $title, string $description, int $userId): Task
    {
        $task = new Task(
            id: null,
            title: $title,
            description: $description,
            isDone: false,
            userId: $userId,
            createdAt: new DateTimeImmutable()
        );

        $task = $this->repository->save($task);

        $this->dispatcher->dispatch(new TaskWasCreated($task));

        return $task;
    }
}
