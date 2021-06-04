<?php

declare(strict_types=1);

namespace App\Model\Task\Entity\Task;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Id
{
    public function __construct(private string $id)
    {
        Assert::notEmpty($id);
        Assert::uuid($id);
    }

    public static function generate(): self
    {
        return new Id(Uuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
