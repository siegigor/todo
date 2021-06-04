<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Name
{
    #[ORM\Column]
    private string $first;

    #[ORM\Column]
    private string $last;

    #[ORM\Column(nullable: true)]
    private ?string $middle;

    public function __construct(string $first, string $last, ?string $middle = null)
    {
        Assert::notEmpty($first);
        Assert::notEmpty($last);

        $this->first = $first;
        $this->last = $last;
        $this->middle = $middle;
    }

    public function getFirst(): ?string
    {
        return $this->first;
    }

    public function getLast(): ?string
    {
        return $this->last;
    }

    public function getMiddle(): ?string
    {
        return $this->middle;
    }

    public function getFull(): string
    {
        return $this->first . ' ' . ($this->middle ? $this->middle . ' ' : '')  . $this->last;
    }
}
