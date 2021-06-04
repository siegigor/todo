<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "user_users")]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: "user_user_id")]
    private Id $id;

    #[ORM\Embedded(class: Name::class)]
    private Name $name;

    #[ORM\Column(type: "user_user_email", unique: true)]
    private Email $email;

    #[ORM\Column()]
    private string $passwordHash;

    public function __construct(Id $id, Name $name, Email $email, string $passwordHash)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}
