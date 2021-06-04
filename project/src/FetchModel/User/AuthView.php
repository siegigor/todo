<?php

declare(strict_types=1);

namespace App\FetchModel\User;

class AuthView
{
    public function __construct(
        private string $id,
        private string $email,
        private string $passwordHash,
        private string $name
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
