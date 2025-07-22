<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\{CreateTask, ListTasks};
use App\Infrastructure\Persistence\InMemoryTaskRepository;

final class ListTasksTest extends TestCase
{
    public function testListsTasksByUser(): void
    {
        $repo = new InMemoryTaskRepository();
        $create = new CreateTask($repo);
        $list = new ListTasks($repo);

        $create->execute('Task A', 'desc', 1);
        $create->execute('Task B', 'desc', 1);
        $create->execute('Other User Task', 'desc', 2);

        $tasks = $list->execute(1);

        $this->assertCount(2, $tasks);
        $this->assertEquals('Task A', $tasks[0]->title);
        $this->assertEquals(1, $tasks[0]->userId);
    }
}
