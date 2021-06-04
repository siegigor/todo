<?php

declare(strict_types=1);

namespace App\Model\Task\Entity\Task;

use Webmozart\Assert\Assert;

class Priority
{
    private const PRIORITY_MIN = 1;
    private const PRIORITY_MAX = 5;

    public function __construct(private int $priority)
    {
        Assert::greaterThanEq($priority, self::PRIORITY_MIN);
        Assert::lessThanEq($priority, self::PRIORITY_MAX);
    }

    public function getValue(): int
    {
        return $this->priority;
    }
}
