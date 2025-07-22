<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\{CreateTask, DeleteTask};
use App\Infrastructure\Persistence\InMemoryTaskRepository;

final class DeleteTaskTest extends TestCase
{
    public function testDeletesTask(): void
    {
        $repo = new InMemoryTaskRepository();
        $create = new CreateTask($repo);
        $delete = new DeleteTask($repo);

        $task = $create->execute('ToDelete', 'Soon gone', 7);

        $this->assertNotNull($repo->findById($task->id));

        $delete->execute($task->id, 7);

        $this->assertNull($repo->findById($task->id));
    }
}
