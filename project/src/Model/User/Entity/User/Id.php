<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Id
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::uuid($value);
        $this->value = $value;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
