<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\EntityNotFoundException;

interface UserRepository
{
    /**
     * @param Id $id
     * @return User
     * @throws EntityNotFoundException
     */
    public function get(Id $id): User;

    /**
     * @param Email $email
     * @return User
     * @throws EntityNotFoundException
     */
    public function getByEmail(Email $email): User;

    public function hasByEmail(Email $email): bool;

    public function add(User $user): void;
}
