<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Task\Entity\Task;

use App\Model\Task\Entity\Task\AuthorId;
use App\Model\Task\Entity\Task\Id;
use App\Model\Task\Entity\Task\Priority;
use App\Model\Task\Entity\Task\Task;
use App\Tests\Builder\Task\TaskBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateTest extends TestCase
{
    public function testCreate(): void
    {
        $task = new Task(
            $id = Id::generate(),
            $priority = new Priority(1),
            $title = 'Test Task',
            $desc = 'desc',
            $authorId = new AuthorId(Uuid::uuid4()->toString()),
            $createdAt = new \DateTimeImmutable(),
            null
        );

        self::assertEquals($id, $task->getId());
        self::assertEquals($priority, $task->getPriority());
        self::assertEquals($title, $task->getTitle());
        self::assertEquals($desc, $task->getDescription());
        self::assertEquals($authorId, $task->getAuthorId());
        self::assertEquals($createdAt, $task->getCreatedAt());
        self::assertFalse($task->isDone());
        self::assertTrue($task->getChildren()->isEmpty());
        self::assertNull($task->getParent());
        self::assertNull($task->getFinishedAt());
        self::assertTrue($task->canBeDeleted());
    }

    public function testCreateWithParent(): void
    {
        $task = new Task(
            Id::generate(),
            new Priority(1),
            'Test Task',
            'desc',
            new AuthorId(Uuid::uuid4()->toString()),
            new \DateTimeImmutable(),
            $parent = (new TaskBuilder())->build()
        );

        self::assertEquals($parent, $task->getParent());
    }
}
