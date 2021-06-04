<?php

declare(strict_types=1);

namespace App\Model\Task\Command\Finish;

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
        $task->finish(new \DateTimeImmutable());
        $this->flusher->flush();
    }
}
