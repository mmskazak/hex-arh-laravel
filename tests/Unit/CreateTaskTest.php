<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\CreateTask;
use App\Infrastructure\Persistence\InMemoryTaskRepository;

final class CreateTaskTest extends TestCase
{
    public function testCreatesTask(): void
    {
        $repo = new InMemoryTaskRepository();
        $useCase = new CreateTask($repo);

        $task = $useCase->execute(
            title: 'Test Title',
            description: 'Test Description',
            userId: 1
        );

        $this->assertNotNull($task->id);
        $this->assertEquals('Test Title', $task->title);
        $this->assertEquals('Test Description', $task->description);
        $this->assertFalse($task->isDone);
        $this->assertEquals(1, $task->userId);
    }
}
