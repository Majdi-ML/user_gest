<?php

namespace App\Repository;

use App\Entity\TypeRassmblement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeRassmblement>
 *
 * @method TypeRassmblement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeRassmblement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeRassmblement[]    findAll()
 * @method TypeRassmblement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRassmblementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeRassmblement::class);
    }

//    /**
//     * @return TypeRassmblement[] Returns an array of TypeRassmblement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeRassmblement
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
