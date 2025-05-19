<?php

namespace App\Repository;

use App\Entity\FonctionEmploye;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FonctionEmploye>
 *
 * @method FonctionEmploye|null find($id, $lockMode = null, $lockVersion = null)
 * @method FonctionEmploye|null findOneBy(array $criteria, array $orderBy = null)
 * @method FonctionEmploye[]    findAll()
 * @method FonctionEmploye[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FonctionEmployeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FonctionEmploye::class);
    }

//    /**
//     * @return FonctionEmploye[] Returns an array of FonctionEmploye objects
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

//    public function findOneBySomeField($value): ?FonctionEmploye
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
