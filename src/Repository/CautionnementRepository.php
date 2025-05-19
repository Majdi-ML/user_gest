<?php

namespace App\Repository;

use App\Entity\Cautionnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cautionnement>
 *
 * @method Cautionnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cautionnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cautionnement[]    findAll()
 * @method Cautionnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CautionnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cautionnement::class);
    }public function findMonthlyTotals(): array
{
    return $this->createQueryBuilder('c')
        ->select('c.Mois as month, SUM(f.montant) as total')
        ->join('c.Montant', 'f')
        ->groupBy('c.Mois')
        ->orderBy('c.Mois')
        ->getQuery()
        ->getResult();
}

public function findYearlyTotals(): array
{
    return $this->createQueryBuilder('c')
        ->select('c.annee as year, SUM(f.montant) as total')
        ->join('c.Montant', 'f')
        ->groupBy('c.annee')
        ->orderBy('c.annee')
        ->getQuery()
        ->getResult();
}

public function findLatest(int $limit): array
{
    return $this->createQueryBuilder('c')
        ->join('c.Personne', 'p')
        ->join('c.appartement', 'a')
        ->join('c.Montant', 'f')
        ->orderBy('c.date_paiement', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}
//    /**
//     * @return Cautionnement[] Returns an array of Cautionnement objects
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

//    public function findOneBySomeField($value): ?Cautionnement
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
