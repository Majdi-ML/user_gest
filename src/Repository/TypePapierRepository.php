<?php

namespace App\Repository;

use App\Entity\TypePapier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypePapier>
 *
 * @method TypePapier|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePapier|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePapier[]    findAll()
 * @method TypePapier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePapierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypePapier::class);
    }

//    /**
//     * @return TypePapier[] Returns an array of TypePapier objects
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

//    public function findOneBySomeField($value): ?TypePapier
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
