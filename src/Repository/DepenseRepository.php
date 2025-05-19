<?php

namespace App\Repository;

use App\Entity\Depense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Depense>
 *
 * @method Depense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depense[]    findAll()
 * @method Depense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depense::class);
    }

    public function findMonthlyTotals(): array
{
    $conn = $this->getEntityManager()->getConnection();

    $sql = "
        SELECT 
            strftime('%Y-%m', date_depense) as month,
            SUM(montant) as total
        FROM depense
        GROUP BY month
        ORDER BY month
    ";

    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();

    return $resultSet->fetchAllAssociative();
}


public function findYearlyTotals(): array
{
    $conn = $this->getEntityManager()->getConnection();

    $sql = "
        SELECT 
            strftime('%Y', date_depense) as year,
            SUM(montant) as total
        FROM depense
        GROUP BY year
        ORDER BY year
    ";

    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();

    return $resultSet->fetchAllAssociative();
}

public function findTotalsByType(): array
{
    return $this->createQueryBuilder('d')
        ->select('d.type, SUM(d.montant) as total')
        ->groupBy('d.type')
        ->getQuery()
        ->getResult();
}

public function findLatest(int $limit): array
{
    return $this->createQueryBuilder('d')
        ->orderBy('d.date_depense', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}
}