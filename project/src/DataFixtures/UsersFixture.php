<?php

namespace App\DataFixtures;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class UsersFixture extends Fixture
{
    private const USERS = [
        [
            'email' => 'test1@gmail.com',
            'name' => [
                'first' => 'Test',
                'last' => 'First'
            ],
            'password' => 'todo1'
        ],
        [
            'email' => 'test2@gmail.com',
            'name' => [
                'first' => 'Test',
                'last' => 'Second'
            ],
            'password' => 'todo2'
        ]
    ];

    public function __construct(private PasswordHasher $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userData) {
            $user = new User(
                Id::generate(),
                new Name($userData['name']['first'], $userData['name']['last']),
                new Email($userData['email']),
                $this->hasher->hash($userData['password'])
            );
            $manager->persist($user);
        }
        $manager->flush();
    }
}
