<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @extends ServiceEntityRepository<Member>
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function findAllWithPagination($page, $limit)
    {
        $qb = $this->createQueryBuilder('b')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }

    public function countMembersThisMonth(): int
    {
        $start = (new \DateTime('first day of this month'))->setTime(0, 0, 0);
        $end = (new \DateTime('last day of this month'))->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->where('m.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function countMembersThisYear(): int
    {
        $start = (new \DateTime('first day of January'))->setTime(0, 0, 0);
        $end = (new \DateTime('last day of December'))->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->where('m.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /* public function countFilteredStats(?int $year = null, ?int $month = null): array
    {
        $start = null;
        $end = null;

        if ($year || $month) {
            $start = new \DateTime(($year ?? date('Y')) . '-' . ($month ?? '01') . '-01 00:00:00');
            $end = (clone $start)->modify($month ? 'last day of this month' : 'last day of December')->setTime(23, 59, 59);
        }

        $qbBase = function () use ($start, $end) {
            $qb = $this->createQueryBuilder('m');

            if ($start && $end) {
                $qb->andWhere('m.createdAt BETWEEN :start AND :end')
                    ->setParameter('start', $start)
                    ->setParameter('end', $end);
            }

            return $qb;
        };

        $members = (clone $qbBase())
            ->select('COUNT(m.id)')
            ->andWhere('m.isMember = true')
            ->getQuery()
            ->getSingleScalarResult();

        $baptized = (clone $qbBase())
            ->select('COUNT(m.id)')
            ->andWhere('m.isBaptized = true')
            ->getQuery()
            ->getSingleScalarResult();

        $withTransport = (clone $qbBase())
            ->select('COUNT(m.id)')
            ->andWhere('m.hasTransport = true')
            ->getQuery()
            ->getSingleScalarResult();

        $inHomeCell = (clone $qbBase())
            ->select('COUNT(m.id)')
            ->andWhere('m.isInHomeCell = true')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'members' => (int) $members,
            'baptized' => (int) $baptized,
            'withTransport' => (int) $withTransport,
            'inHomeCell' => (int) $inHomeCell
        ];
    }
 */

    public function getStats(?int $year = null, ?int $month = null): array
    {
        $start = null;
        $end = null;

        if ($year) {
            if ($month != null) {
                // Case 1: Year and month are provided. Get stats for that specific month.
                $correctMonth = $month < 10 ? '0' . $month : $month;
                $dateString = "$year-$correctMonth-01";
                $start = new \DateTime($dateString . ' 00:00:00');
                $end = (clone $start)->modify('last day of this month')->setTime(23, 59, 59);
            } else {
                // Case 2: Only year is provided. Get stats for the whole year.
                $dateString = "$year-01-01";
                $start = new \DateTime($dateString . ' 00:00:00');
                $end = (clone $start)->modify('last day of December')->setTime(23, 59, 59);
            }
        }


        $baseQB = function () use ($start, $end) {
            $qb = $this->createQueryBuilder('m');

            if ($start && $end) {
                $qb->andWhere('m.createdAt BETWEEN :start AND :end')
                    ->setParameter('start', $start)
                    ->setParameter('end', $end);
            }

            return $qb;
        };

        $createdCount = (clone $baseQB())
            ->select('COUNT(m.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $members = (clone $baseQB())
            ->select('COUNT(m.id)')
            ->andWhere('m.isMember = true')
            ->getQuery()
            ->getSingleScalarResult();

        $visitors = (clone $baseQB())
            ->select('COUNT(m.id)')
            ->andWhere('m.isMember = false')
            ->getQuery()
            ->getSingleScalarResult();

        $baptized = (clone $baseQB())
            ->select('COUNT(m.id)')
            ->andWhere('m.isBaptized = true')
            ->getQuery()
            ->getSingleScalarResult();

        $withTransport = (clone $baseQB())
            ->select('COUNT(m.id)')
            ->andWhere('m.hasTransport = true')
            ->getQuery()
            ->getSingleScalarResult();

        $inHomeCell = (clone $baseQB())
            ->select('COUNT(m.id)')
            ->andWhere('m.isInHomeCell = true')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'created' => (int) $createdCount,
            'members' => (int) $members,
            'visitors' => (int) $visitors,
            'baptized' => (int) $baptized,
            'withTransport' => (int) $withTransport,
            'inHomeCell' => (int) $inHomeCell,
            'period' => [
                'start' => $start ? $start->format('Y-m-d H:i:s') : 'N/A',
                'end' => $end ? $end->format('Y-m-d H:i:s') : 'N/A',
            ]
        ];
    }


    //    /**
    //     * @return Member[] Returns an array of Member objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Member
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
