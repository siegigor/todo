<?php

declare(strict_types=1);

namespace App\Model\Task\Command\Create;

use App\Model\Flusher;
use App\Model\Task\Entity\Task\AuthorId;
use App\Model\Task\Entity\Task\Id;
use App\Model\Task\Entity\Task\Priority;
use App\Model\Task\Entity\Task\Task;
use App\Model\Task\Entity\Task\TaskRepository;

class Handler
{
    public function __construct(private TaskRepository $tasks, private Flusher $flusher)
    {
    }

    public function __invoke(Command $command): void
    {
        $parent = $command->parent
            ? $this->tasks->getByIdAndAuthorId(new Id($command->parent), new AuthorId($command->authorId))
            : null;

        $task = new Task(
            new Id($command->id),
            new Priority($command->priority),
            $command->title,
            $command->description,
            new AuthorId($command->authorId),
            new \DateTimeImmutable(),
            $parent
        );

        $this->tasks->add($task);
        $this->flusher->flush();
    }
}
