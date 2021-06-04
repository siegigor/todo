<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Task\Entity\Task;

use App\Tests\Builder\Task\TaskBuilder;
use Monolog\Test\TestCase;

class FinishTest extends TestCase
{
    public function testSuccess(): void
    {
        $task = (new TaskBuilder())->build();
        $task->finish($finishedAt = new \DateTimeImmutable());

        self::assertTrue($task->isDone());
        self::assertEquals($finishedAt, $task->getFinishedAt());
    }

    public function testFailedByStatus(): void
    {
        $task = (new TaskBuilder())->markAsDone()->build();
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Task was already marked as done');
        $task->finish(new \DateTimeImmutable());
    }

    public function testSuccessWithChildren(): void
    {
        $childTask = (new TaskBuilder())->assignToParent()->markAsDone()->build();
        $taskParent = $childTask->getParent();
        $taskParent->getChildren()->add($childTask);
        $taskParent->finish(new \DateTimeImmutable());
        self::assertTrue($taskParent->isDone());
    }

    public function testFailedByChildrenStatus(): void
    {
        $childTask = (new TaskBuilder())->assignToParent()->build();
        $taskParent = $childTask->getParent();
        $taskParent->getChildren()->add($childTask);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Complete child tasks before closing this one');
        $taskParent->finish(new \DateTimeImmutable());
    }
}
