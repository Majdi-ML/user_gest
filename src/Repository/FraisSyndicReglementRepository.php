<?php

namespace App\Repository;

use App\Entity\FraisSyndicReglement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FraisSyndicReglement>
 *
 * @method FraisSyndicReglement|null find($id, $lockMode = null, $lockVersion = null)
 * @method FraisSyndicReglement|null findOneBy(array $criteria, array $orderBy = null)
 * @method FraisSyndicReglement[]    findAll()
 * @method FraisSyndicReglement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FraisSyndicReglementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FraisSyndicReglement::class);
    }

//    /**
//     * @return FraisSyndicReglement[] Returns an array of FraisSyndicReglement objects
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

//    public function findOneBySomeField($value): ?FraisSyndicReglement
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
