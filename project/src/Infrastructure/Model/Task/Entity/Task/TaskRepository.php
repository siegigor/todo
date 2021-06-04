<?php

declare(strict_types=1);

namespace App\Infrastructure\Model\Task\Entity\Task;

use App\Model\Task\Entity\Task\AuthorId;
use App\Model\Task\Entity\Task\Id;
use App\Model\Task\Entity\Task\Task;
use App\Model\Task\Entity\Task\TaskRepository as TaskRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @var EntityRepository<Task>
     */
    private EntityRepository $repo;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Task::class);
    }

    public function get(Id $id): Task
    {
        /** @var Task $task */
        if (!$task = $this->repo->find($id)) {
            throw new EntityNotFoundException('Task not found');
        }
        return  $task;
    }

    public function getByIdAndAuthorId(Id $id, AuthorId $authorId): Task
    {
        /** @var Task $task */
        if (!$task = $this->repo->findOneBy(['id' => $id, 'authorId' => $authorId])) {
            throw new EntityNotFoundException('Task not found');
        }
        return  $task;
    }

    public function add(Task $task): void
    {
        $this->em->persist($task);
    }

    public function remove(Task $task): void
    {
        $this->em->remove($task);
    }
}
