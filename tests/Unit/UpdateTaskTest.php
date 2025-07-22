<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\{CreateTask, UpdateTask};
use App\Infrastructure\Persistence\InMemoryTaskRepository;

final class UpdateTaskTest extends TestCase
{
    public function testUpdatesTask(): void
    {
        $repo = new InMemoryTaskRepository();
        $create = new CreateTask($repo);
        $update = new UpdateTask($repo);

        $task = $create->execute('Old', 'Old Desc', 42);

        $updated = $update->execute(
            taskId: $task->id,
            title: 'New',
            description: 'New Desc',
            isDone: true,
            userId: 42
        );

        $this->assertEquals('New', $updated->title);
        $this->assertEquals('New Desc', $updated->description);
        $this->assertTrue($updated->isDone);
    }
}
