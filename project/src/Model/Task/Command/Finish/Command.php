<?php

declare(strict_types=1);

namespace App\Model\Task\Command\Finish;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
