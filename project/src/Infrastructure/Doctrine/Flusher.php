<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Model\Flusher as FlusherInterface;
use Doctrine\ORM\EntityManagerInterface;

class Flusher implements FlusherInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
