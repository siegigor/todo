<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ApiUsersFixture extends Fixture
{
    public const USER_1_ID = '8ba9beab-2de2-49d2-871e-2a1a66a16549';
    public const USER_2_ID = '0f766b9e-49cb-4f15-98aa-ad47eaf29f18';

    public function __construct(private PasswordHasher $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User(
            new Id(self::USER_1_ID),
            new Name('User', 'First'),
            new Email(self::user1Credentials()['PHP_AUTH_USER']),
            $this->hasher->hash(self::user1Credentials()['PHP_AUTH_PW'])
        );
        $manager->persist($user1);

        $user2 = new User(
            new Id(self::USER_2_ID),
            new Name('User', 'Second'),
            new Email(self::user2Credentials()['PHP_AUTH_USER']),
            $this->hasher->hash(self::user2Credentials()['PHP_AUTH_PW'])
        );
        $manager->persist($user2);

        $manager->flush();
    }

    public static function user1Credentials(): array
    {
        return [
            'PHP_AUTH_USER' => 'testuser1@gmail.com',
            'PHP_AUTH_PW' => 'testuser1',
        ];
    }

    public static function user2Credentials(): array
    {
        return [
            'PHP_AUTH_USER' => 'testuser2@gmail.com',
            'PHP_AUTH_PW' => 'testuser2',
        ];
    }
}
