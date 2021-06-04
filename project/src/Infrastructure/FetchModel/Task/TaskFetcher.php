<?php

declare(strict_types=1);

namespace App\Infrastructure\FetchModel\Task;

use App\FetchModel\Task\Filter;
use App\FetchModel\Task\Sort;
use App\FetchModel\Task\TaskFetcher as TaskFetcherInterface;
use Doctrine\DBAL\Connection;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class TaskFetcher implements TaskFetcherInterface
{
    public function __construct(private Connection $connection, private PaginatorInterface $paginator)
    {
    }

    public function findBy(Filter $filter, Sort $sort, string $authorId, int $page): PaginationInterface
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('task_tasks', 't')
            ->where('author_id = :author_id')
            ->setParameter(':author_id', $authorId);

        if ($filter->getStatus()) {
            $query->andWhere('status = :status')
                ->setParameter(':status', $filter->getStatus());
        }

        if ($filter->getPriorityFrom()) {
            $query->andWhere('priority >= :priorityFrom')
                ->setParameter(':priorityFrom', $filter->getPriorityFrom());
        }

        if ($filter->getPriorityTo()) {
            $query->andWhere('priority <= :priorityTo')
                ->setParameter(':priorityTo', $filter->getPriorityTo());
        }

        if ($filter->getTitle()) {
            $vector = "to_tsvector(title)";
            $queryString = 'plainto_tsquery(:title)';
            $query->andWhere($query->expr()->or(
                $query->expr()->like('LOWER(title)', ':title'),
                "$vector @@ $queryString"
            ))
                ->setParameter(':title', '%' . mb_strtolower($filter->getTitle()) . '%');
        }

        if ($sort->getSortBy()) {
            $query->orderBy($sort->getSortBy(), $sort->getDirection() ?: 'asc');
        }

        return $this->paginator->paginate($query, $page);
    }
}
