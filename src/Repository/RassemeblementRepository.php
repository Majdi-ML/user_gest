<?php

namespace App\Repository;

use App\Entity\Rassemeblement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rassemeblement>
 *
 * @method Rassemeblement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rassemeblement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rassemeblement[]    findAll()
 * @method Rassemeblement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RassemeblementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rassemeblement::class);
    }

//    /**
//     * @return Rassemeblement[] Returns an array of Rassemeblement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rassemeblement
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
