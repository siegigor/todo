<?php

declare(strict_types=1);

namespace App\Model\Task\Entity\Task;

interface TaskRepository
{
    public function get(Id $id): Task;

    public function getByIdAndAuthorId(Id $id, AuthorId $authorId): Task;

    public function add(Task $task): void;

    public function remove(Task $task): void;
}
