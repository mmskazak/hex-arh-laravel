<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;
use DateTimeImmutable;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function findById(int $id): ?Task
    {
        $model = EloquentTaskModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findAllByUserId(int $userId): array
    {
        return EloquentTaskModel::where('user_id', $userId)
            ->get()
            ->map(fn($model) => $this->toEntity($model))
            ->all();
    }

    public function save(Task $task): Task
    {
        $model = new EloquentTaskModel([
            'title' => $task->title,
            'description' => $task->description,
            'is_done' => $task->isDone,
            'user_id' => $task->userId,
            'created_at' => $task->createdAt->format('Y-m-d H:i:s'),
        ]);

        $model->save();

        return new Task(
            id: $model->id,
            title: $model->title,
            description: $model->description,
            isDone: $model->is_done,
            userId: $model->user_id,
            createdAt: new DateTimeImmutable($model->created_at)
        );
    }

    public function delete(int $id): void
    {
        EloquentTaskModel::destroy($id);
    }

    private function toEntity(EloquentTaskModel $model): Task
    {
        return new Task(
            id: $model->id,
            title: $model->title,
            description: $model->description,
            isDone: $model->is_done,
            userId: $model->user_id,
            createdAt: new DateTimeImmutable($model->created_at)
        );
    }
}
