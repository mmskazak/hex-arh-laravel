<?php

namespace App\Domain\Events;

use App\Domain\Entities\Task;

class TaskWasCreated
{
    public function __construct(
        public readonly Task $task
    ) {}
}
