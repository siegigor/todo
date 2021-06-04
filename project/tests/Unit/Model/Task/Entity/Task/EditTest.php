<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Task\Entity\Task;

use App\Model\Task\Entity\Task\Priority;
use App\Tests\Builder\Task\TaskBuilder;
use Monolog\Test\TestCase;

class EditTest extends TestCase
{
    public function testSuccess()
    {
        $task = (new TaskBuilder())->build();
        $parentTask = (new TaskBuilder())->build();
        $task->edit(
            $priority = new Priority(2),
            $title = 'Test Task modified',
            $desc = 'modified',
            $parentTask
        );

        self::assertEquals($priority, $task->getPriority());
        self::assertEquals($title, $task->getTitle());
        self::assertEquals($desc, $task->getDescription());
        self::assertEquals($parentTask, $task->getParent());
        self::assertFalse($task->isDone());
    }
}
