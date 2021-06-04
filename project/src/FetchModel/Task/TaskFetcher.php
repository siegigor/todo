<?php

declare(strict_types=1);

namespace App\FetchModel\Task;

use Knp\Component\Pager\Pagination\PaginationInterface;

interface TaskFetcher
{
    public function findBy(Filter $filter, Sort $sort, string $authorId, int $page): PaginationInterface;
}
