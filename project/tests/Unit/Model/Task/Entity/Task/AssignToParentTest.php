<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Task\Entity\Task;

use App\Tests\Builder\Task\TaskBuilder;
use Monolog\Test\TestCase;

class AssignToParentTest extends TestCase
{
    public function testSuccess(): void
    {
        $taskParent = (new TaskBuilder())->build();
        $taskChild = (new TaskBuilder())->assignToParent($taskParent)->build();
        self::assertEquals($taskChild->getParent(), $taskParent);
    }

    public function testFaildByParent(): void
    {
        $taskParent = (new TaskBuilder())->build();
        $taskChild1 = (new TaskBuilder())->assignToParent($taskParent)->build();
        $taskChild2 = (new TaskBuilder())->assignToParent($taskChild1)->build();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Task can't be its own parent");
        $taskParent->assignToParent($taskChild2);
    }
}
