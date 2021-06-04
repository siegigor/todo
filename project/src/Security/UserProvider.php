<?php

declare(strict_types=1);

namespace App\Security;

use App\FetchModel\User\AuthView;
use App\FetchModel\User\UserFetcher;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(private UserFetcher $users)
    {
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        $user = $this->loadUser($username);
        return self::identityByUser($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserIdentity) {
            throw new UnsupportedUserException('Invalid user class ' . \get_class($user));
        }
        $userView = $this->loadUser($user->getUsername());
        return self::identityByUser($userView);
    }

    public function supportsClass(string $class): bool
    {
        return $class === UserIdentity::class;
    }

    private function loadUser(string $username): AuthView
    {
        if ($user = $this->users->findForAuth($username)) {
            return $user;
        }

        throw new UsernameNotFoundException('User not found');
    }

    private static function identityByUser(AuthView $user): UserIdentity
    {
        return new UserIdentity(
            $user->getId(),
            $user->getEmail(),
            $user->getPasswordHash(),
            $user->getName()
        );
    }
}
