<?php

declare(strict_types=1);

namespace App\Model\Task\Entity\Task;

use Webmozart\Assert\Assert;

class Status
{
    private const STATUS_TODO = 'todo';
    private const STATUS_DONE = 'done';

    public function __construct(private string $status)
    {
        Assert::inArray($this->status, self::all());
    }

    /**
     * @return string[]
     */
    public static function all(): array
    {
        return [
            self::STATUS_TODO,
            self::STATUS_DONE
        ];
    }

    public static function todo(): self
    {
        return new self(self::STATUS_TODO);
    }

    public static function done(): self
    {
        return new self(self::STATUS_DONE);
    }

    public function isDone(): bool
    {
        return $this->status === self::STATUS_DONE;
    }

    public function getValue(): string
    {
        return $this->status;
    }
}
