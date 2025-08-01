<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements
    PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(
        PasswordAuthenticatedUserInterface $user,
        string $newHashedPassword
    ): void {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are
not supported.', $user::class));
        }
        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    public function findAllWithPagination($page, $limit)
    {
        $qb = $this->createQueryBuilder('b')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }

    public function findAllWithRole(?string $role = null)
    {
        $qb = $this->createQueryBuilder('u');
        if ($role) {
            $qb->andWhere('u.roles LIKE :role')
                ->setParameter('role', '%' . $role . '%');
        }
        return $qb->getQuery()->getResult();
    }

    public function findAllWithPaginationAndRole(
        int $page,
        int $limit,
        ?string $role = null
    ): array {
        $qb = $this->createQueryBuilder('u');
        if ($role) {
            $qb->andWhere('u.roles LIKE :role')
                ->setParameter('role', '%' . $role . '%');
        }
        return $qb
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    // /**
    // * @return User[] Returns an array of User objects
    // */
    // public function findByExampleField($value): array
    // {
    // return $this->createQueryBuilder('u')
    // ->andWhere('u.exampleField = :val')
    // ->setParameter('val', $value)
    // ->orderBy('u.id', 'ASC')
    // ->setMaxResults(10)
    // ->getQuery()
    // ->getResult()
    // ;
    // }
    // public function findOneBySomeField($value): ?User
    // {
    // return $this->createQueryBuilder('u')
    // ->andWhere('u.exampleField = :val')
    // ->setParameter('val', $value)
    // ->getQuery()
    // ->getOneOrNullResult()
    // ;
    // }
}
