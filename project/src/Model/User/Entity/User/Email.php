<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::email($value);
        $this->value = mb_strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(self $email): bool
    {
        return $this->getValue() === $email->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
