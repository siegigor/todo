<?php

declare(strict_types=1);

namespace App\Infrastructure\FetchModel\User;

use App\FetchModel\User\AuthView;
use App\FetchModel\User\UserFetcher as UserFetcherInterface;
use Doctrine\DBAL\Connection;

class UserFetcher implements UserFetcherInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function findForAuth(string $email): ?AuthView
    {
        /**
         * @var array{id: string, email: string, password_hash: string, name: string}|null $user
         * @psalm-suppress TooManyArguments
         * @psalm-suppress PossiblyInvalidMethodCall
         */
        $user = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'email',
                'password_hash',
                'TRIM(CONCAT(name_first, \' \', name_last)) AS name'
            )
            ->from('user_users')
            ->where('email = :email')
            ->setParameter(':email', $email)
            ->execute()
            ->fetchAssociative();
        if (!$user) {
            return null;
        }
        return new AuthView(
            $user['id'] ?? '',
            $user['email'] ?? '',
            $user['password_hash'] ?? '',
            $user['name'] ?? ''
        );
    }
}
