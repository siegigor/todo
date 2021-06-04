<?php

declare(strict_types=1);

namespace App\Model\Task\Entity\Task;

use Webmozart\Assert\Assert;

class AuthorId
{
    public function __construct(private string $id)
    {
        Assert::notEmpty($id);
        Assert::uuid($id);
    }

    public function getValue(): string
    {
        return $this->id;
    }
}
