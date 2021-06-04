<?php

declare(strict_types=1);

namespace App\FetchModel\Task;

use Webmozart\Assert\Assert;

class Sort
{
    private const SORT_OPTIONS = ['created_at', 'finished_at', 'priority'];
    private const DIRECTION_OPTIONS = ['asc', 'desc'];
    private const DEFAULT_SORT_BY = 'created_at';
    private const DEFAULT_DIRECTION = 'asc';

    private string $sortBy;
    private string $direction;

    public function __construct(?string $sortBy, ?string $direction)
    {
        $this->sortBy = $sortBy ?: self::DEFAULT_SORT_BY;
        $this->direction = $direction ?: self::DEFAULT_DIRECTION;

        Assert::inArray($this->sortBy, self::SORT_OPTIONS);
        Assert::inArray($this->direction, self::DIRECTION_OPTIONS);
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
