<?php

declare(strict_types=1);

namespace App\Model\Task\Command\Delete;

use App\Model\Flusher;
use App\Model\Task\Entity\Task\Id;
use App\Model\Task\Entity\Task\TaskRepository;

class Handler
{
    public function __construct(private TaskRepository $tasks, private Flusher $flusher)
    {
    }

    public function __invoke(Command $command): void
    {
        $task = $this->tasks->get(new Id($command->id));

        if (!$task->canBeDeleted()) {
            throw new \DomainException('Task can\'t be deleted if it was already done');
        }

        $this->tasks->remove($task);
        $this->flusher->flush();
    }
}
