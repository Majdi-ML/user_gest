<?php

namespace App\Repository;

use App\Entity\StatusBureau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StatusBureau>
 *
 * @method StatusBureau|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusBureau|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusBureau[]    findAll()
 * @method StatusBureau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusBureauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatusBureau::class);
    }

//    /**
//     * @return StatusBureau[] Returns an array of StatusBureau objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StatusBureau
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
