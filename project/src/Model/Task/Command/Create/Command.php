<?php

declare(strict_types=1);

namespace App\Model\Task\Command\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 5)]
    public int $priority = 0;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $title = '';

    #[Assert\NotBlank]
    public string $description = '';

    #[Assert\Uuid]
    public ?string $parent = null;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $authorId;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $id;

    public function __construct(string $id, string $authorId)
    {
        $this->id = $id;
        $this->authorId = $authorId;
    }
}
