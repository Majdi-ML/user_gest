<?php

namespace App\Repository;

use App\Entity\FraisSyndic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FraisSyndic>
 *
 * @method FraisSyndic|null find($id, $lockMode = null, $lockVersion = null)
 * @method FraisSyndic|null findOneBy(array $criteria, array $orderBy = null)
 * @method FraisSyndic[]    findAll()
 * @method FraisSyndic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FraisSyndicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FraisSyndic::class);
    }

//    /**
//     * @return FraisSyndic[] Returns an array of FraisSyndic objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FraisSyndic
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
