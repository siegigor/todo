<?php

declare(strict_types=1);

namespace App\FetchModel\Task;

use Webmozart\Assert\Assert;

class Filter
{
    private const STATUSES = ['todo', 'done'];
    private const PRIORITIES_FROM = 1;
    private const PRIORITIES_TO = 5;

    public function __construct(
        private ?string $status,
        private ?int $priorityFrom,
        private ?int $priorityTo,
        private ?string $title
    ) {
        if ($this->status) {
            Assert::inArray($this->status, self::STATUSES);
        }
        if ($this->priorityFrom) {
            Assert::greaterThanEq($priorityFrom, self::PRIORITIES_FROM);
            Assert::lessThanEq($priorityFrom, self::PRIORITIES_TO);
        }
        if ($this->priorityTo) {
            Assert::greaterThanEq($priorityTo, self::PRIORITIES_FROM);
            Assert::lessThanEq($priorityTo, self::PRIORITIES_TO);
        }
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getPriorityFrom(): ?int
    {
        return $this->priorityFrom;
    }

    public function getPriorityTo(): ?int
    {
        return $this->priorityTo;
    }

    /**
     * @psalm-mutation-free
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
}
