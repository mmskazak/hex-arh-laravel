<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Task;

interface TaskRepositoryInterface
{
    public function findById(int $id): ?Task;

    public function findAllByUserId(int $userId): array;

    public function save(Task $task): Task;

    public function delete(int $id): void;
}
