<?php

declare(strict_types=1);

namespace App\Model\User\Service;

class PasswordHasher
{
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2I);
        if ($hash === null) {
            throw new \RuntimeException('Password generation failed');
        }
        return $hash;
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
