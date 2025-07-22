<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;
use DateTimeImmutable;
use App\Domain\Events\TaskWasCreated;
use App\Infrastructure\Events\Dispatcher;
use App\Infrastructure\EventSourcing\EventRecorder;


class CreateTask
{
    public function __construct(
        private readonly TaskRepositoryInterface $repository,
        private readonly EventRecorder $eventRecorder,
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

        $event = new TaskWasCreated($task);

        // 🔥 Записываем в Event Store
        $this->eventRecorder->record((string) $task->id, $event);

        $this->dispatcher->dispatch($event);

        return $task;
    }
}
