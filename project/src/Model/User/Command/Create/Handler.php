<?php

declare(strict_types=1);

namespace App\Model\User\Command\Create;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\PasswordHasher;

class Handler
{
    public function __construct(private UserRepository $users, private PasswordHasher $hasher, private Flusher $flusher)
    {
    }

    public function handle(Command $command): void
    {
        if ($this->users->hasByEmail(new Email($command->email))) {
            throw new \DomainException('User already exists.');
        }

        $user = new User(
            Id::generate(),
            new Name($command->firstName, $command->lastName),
            new Email($command->email),
            $this->hasher->hash($command->password)
        );
        $this->users->add($user);
        $this->flusher->flush();
    }
}
