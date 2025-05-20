<?php

namespace App\Repository;

use App\Entity\DossierSinistre;
use App\Entity\YearlySequence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DossierSinistre>
 */
class DossierSinistreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DossierSinistre::class);
    }

    public function getNextId(): int
    {
        // Option 1: Get max ID from database
        $result = $this->createQueryBuilder('d')
            ->select('MAX(d.id) as max_id')
            ->getQuery()
            ->getSingleResult();

        return ($result['max_id'] ?? 0) + 1;

        // Option 2: Count all records (if your IDs have gaps)
        // return $this->count([]) + 1;
    }

    public function getNextYearlyId(EntityManagerInterface $em): int
    {
        $em->beginTransaction();
        try {
            $currentYear = date('y');
            $sequence = $em->getRepository(YearlySequence::class)->find($currentYear, \Doctrine\DBAL\LockMode::PESSIMISTIC_WRITE);

            if (!$sequence) {
                $sequence = new YearlySequence();
                $sequence->setYear($currentYear);
                $sequence->setValue(1);
                $em->persist($sequence);
                $em->flush();
                $em->commit();
                return 1;
            }

            $nextValue = $sequence->getValue() + 1;
            $sequence->setValue($nextValue);
            $em->flush();
            $em->commit();
            return $nextValue;
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
    }

    //    /**
    //     * @return DossierSinistre[] Returns an array of DossierSinistre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DossierSinistre
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
