<?php

namespace App\Domain\Aggregates;

use App\Domain\Entities\Task;
use App\Domain\Events\TaskWasCreated;
use App\Infrastructure\EventSourcing\StoredEvent;
use DateTimeImmutable;

class TaskAggregate
{
    public ?Task $task = null;

    public static function reconstitute(string $taskId): self
    {
        $aggregate = new self();

        $events = StoredEvent::where('aggregate_id', $taskId)
            ->orderBy('occurred_at')
            ->get();

        foreach ($events as $stored) {
            $eventClass = $stored->event_type;
            $payload = $stored->payload;

            $event = new $eventClass(...array_values($payload));
            $aggregate->apply($event);
        }

        return $aggregate;
    }

    public function apply(object $event): void
    {
        $method = 'apply' . class_basename($event::class);

        if (method_exists($this, $method)) {
            $this->$method($event);
        }
    }

    protected function applyTaskWasCreated(TaskWasCreated $event): void
    {
        $task = $event->task;

        $this->task = new Task(
            id: $task['id'],
            title: $task['title'],
            description: $task['description'],
            isDone: $task['isDone'],
            userId: $task['userId'],
            createdAt: new DateTimeImmutable($task['createdAt'])
        );
    }
}
