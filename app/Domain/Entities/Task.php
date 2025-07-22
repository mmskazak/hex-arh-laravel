<?php

namespace App\Domain\Entities;

use DateTimeImmutable;

class Task
{
    public function __construct(
        public readonly ?int $id,
        public string $title,
        public string $description,
        public bool $isDone,
        public readonly int $userId,
        public readonly DateTimeImmutable $createdAt,
    ) {}

    public function markAsDone(): void
    {
        $this->isDone = true;
    }

    public function markAsUndone(): void
    {
        $this->isDone = false;
    }
}
