<?php

declare(strict_types=1);

namespace App\Infrastructure\Model\User\Entity\User;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository as UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class UserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $em;
    /**
     * @var EntityRepository<User>
     */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
    }

    /**
     * @param Id $id
     * @return User
     * @throws EntityNotFoundException
     */
    public function get(Id $id): User
    {
        /** @var User $user */
        if (!$user = $this->repo->find($id)) {
            throw new EntityNotFoundException('User not found');
        }
        return $user;
    }

    /**
     * @param Email $email
     * @return User
     * @throws EntityNotFoundException
     */
    public function getByEmail(Email $email): User
    {
        /** @var User $user */
        if (!$user = $this->repo->findOneBy(['email' => $email])) {
            throw new EntityNotFoundException('User not found.');
        }
        return $user;
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->andWhere('u.email = :email')
                ->setParameter(':email', $email->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }
}
