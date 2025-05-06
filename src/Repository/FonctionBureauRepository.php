<?php

namespace App\Repository;

use App\Entity\FonctionBureau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FonctionBureau>
 *
 * @method FonctionBureau|null find($id, $lockMode = null, $lockVersion = null)
 * @method FonctionBureau|null findOneBy(array $criteria, array $orderBy = null)
 * @method FonctionBureau[]    findAll()
 * @method FonctionBureau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FonctionBureauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FonctionBureau::class);
    }

//    /**
//     * @return FonctionBureau[] Returns an array of FonctionBureau objects
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

//    public function findOneBySomeField($value): ?FonctionBureau
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
