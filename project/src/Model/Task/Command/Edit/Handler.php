<?php

declare(strict_types=1);

namespace App\Model\Task\Command\Edit;

use App\Model\Flusher;
use App\Model\Task\Entity\Task\Id;
use App\Model\Task\Entity\Task\Priority;
use App\Model\Task\Entity\Task\TaskRepository;

class Handler
{
    public function __construct(private TaskRepository $tasks, private Flusher $flusher)
    {
    }

    public function __invoke(Command $command): void
    {
        $task = $this->tasks->get(new Id($command->id));
        $parent = $command->parent
            ? $this->tasks->getByIdAndAuthorId(new Id($command->parent), $task->getAuthorId())
            : null;

        $task->edit(
            new Priority($command->priority),
            $command->title,
            $command->description,
            $parent
        );

        $this->flusher->flush();
    }
}
