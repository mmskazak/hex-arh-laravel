<?php

namespace App\Application\UseCases;

use App\Domain\Interfaces\TaskRepositoryInterface;

class ListTasks
{
    public function __construct(
        private readonly TaskRepositoryInterface $repository
    ) {}

    public function execute(int $userId): array
    {
        return $this->repository->findAllByUserId($userId);
    }
}
