<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Model\Task\Entity\Task\Task;
use App\Security\UserIdentity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskAccess extends Voter
{
    public const ACCESS_MANAGE = 'manage';

    /**
     * @param string $attribute
     * @param Task $subject
     * @return bool
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    protected function supports(string $attribute, $subject)
    {
        return in_array($attribute, [self::ACCESS_MANAGE]) && $subject instanceof Task;
    }

    /**
     * @param string $attribute
     * @param Task $subject
     * @param TokenInterface $token
     * @return bool
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        /** @var UserIdentity|null $user */
        $user = $token->getUser();

        if (!$user instanceof UserIdentity) {
            return false;
        }

        if ($attribute === self::ACCESS_MANAGE) {
            return $subject->getAuthorId()->getValue() === $user->getId();
        }

        return false;
    }
}
