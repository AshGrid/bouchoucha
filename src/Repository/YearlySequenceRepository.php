<?php

namespace App\Repository;

use App\Entity\YearlySequence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<YearlySequence>
 */
class YearlySequenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YearlySequence::class);
    }

    public function getNextYearlyId(EntityManagerInterface $em): int
    {
        $currentYear = date('y'); // 2-digit year (e.g., "24")

        // Find or create the sequence for the current year
        $sequence = $em->getRepository(YearlySequence::class)->find($currentYear);

        if (!$sequence) {
            $sequence = new YearlySequence();
            $sequence->setYear($currentYear);
            $sequence->setValue(1);
            $em->persist($sequence);
            return 1;
        }

        // Increment and return the next value
        $nextValue = $sequence->getValue() + 1;
        $sequence->setValue($nextValue);
        return $nextValue;
    }

    //    /**
    //     * @return YearlySequence[] Returns an array of YearlySequence objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('y')
    //            ->andWhere('y.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('y.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?YearlySequence
    //    {
    //        return $this->createQueryBuilder('y')
    //            ->andWhere('y.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
