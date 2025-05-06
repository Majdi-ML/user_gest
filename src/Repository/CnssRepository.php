<?php

namespace App\Repository;

use App\Entity\Cnss;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cnss>
 *
 * @method Cnss|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cnss|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cnss[]    findAll()
 * @method Cnss[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CnssRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cnss::class);
    }

//    /**
//     * @return Cnss[] Returns an array of Cnss objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cnss
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
