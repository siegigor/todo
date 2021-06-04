<?php

declare(strict_types=1);

namespace App\Tests\Builder\Task;

use App\Model\Task\Entity\Task\AuthorId;
use App\Model\Task\Entity\Task\Id;
use App\Model\Task\Entity\Task\Priority;
use App\Model\Task\Entity\Task\Task;
use Ramsey\Uuid\Uuid;

class TaskBuilder
{
    private Id $id;
    private Priority $priority;
    private string $title;
    private string $desc;
    private AuthorId $authorId;
    private \DateTimeImmutable $createdAt;
    private ?Task $parent = null;
    private bool $isDone = false;

    public function __construct(?Id $id = null, ?AuthorId $authorId = null)
    {
        $this->id = $id ?: Id::generate();
        $this->priority = new Priority(1);
        $this->title = 'Test Task';
        $this->desc = 'desc';
        $this->authorId = $authorId ?: new AuthorId(Uuid::uuid4()->toString());
        $this->createdAt = new \DateTimeImmutable();
    }

    public function assignToParent(?Task $task = null): self
    {
        $clone = clone $this;
        $clone->parent = $task ?: (new self())->build();

        return $clone;
    }

    public function markAsDone(): self
    {
        $clone = clone $this;
        $clone->isDone = true;

        return $clone;
    }

    public function build(): Task
    {
        $task = new Task(
            $this->id,
            $this->priority,
            $this->title,
            $this->desc,
            $this->authorId,
            $this->createdAt,
            null
        );

        if ($this->parent) {
            $task->assignToParent($this->parent);
        }

        if ($this->isDone) {
            $task->finish(new \DateTimeImmutable());
        }

        return $task;
    }
}
