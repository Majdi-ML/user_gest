<?php

namespace App\Repository;

use App\Entity\Appartement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appartement>
 *
 * @method Appartement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appartement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appartement[]    findAll()
 * @method Appartement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appartement::class);
    }

    public function findDistinctLocataires()
{
    return $this->createQueryBuilder('a')
        ->select('DISTINCT l.id, l.nom, l.prenom') // Sélectionner l'id, nom et prénom des locataires
        ->join('a.locataire', 'l') // Jointure avec l'entité Locataire
        ->orderBy('l.nom', 'ASC') // Tri par nom
        ->addOrderBy('l.prenom', 'ASC') // Tri par prénom
        ->getQuery()
        ->getResult();
}
// src/Repository/AppartementRepository.php

public function findDistinctProprietaires()
{
    return $this->createQueryBuilder('a')
        ->select('DISTINCT p.id, p.nom, p.prenom') // Sélectionner l'id, nom et prénom des propriétaires
        ->join('a.proprietaire', 'p') // Jointure avec l'entité Proprietaire
        ->orderBy('p.nom', 'ASC') // Tri par nom
        ->addOrderBy('p.prenom', 'ASC') // Tri par prénom
        ->getQuery()
        ->getResult();
}
// src/Repository/AppartementRepository.php

public function findDistinctValues(string $field)
{
    // If the field is 'bloc', you need to access a property of the Bloc entity, such as 'nom'
    $qb = $this->createQueryBuilder('a');

    // Check if the field is 'bloc' and adjust the select query
    if ($field === 'bloc') {
        $qb->select('DISTINCT b.nom') // Assuming 'nom' is the property you want from Bloc
            ->join('a.bloc', 'b'); // Join with the Bloc entity
    } else {
        $qb->select('DISTINCT a.' . $field); // For other fields, select directly from Appartement
    }

    $qb->where('a.' . $field . ' IS NOT NULL')
       ->orderBy('a.' . $field, 'ASC');

    return $qb->getQuery()->getResult();
}


//    /**
//     * @return Appartement[] Returns an array of Appartement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Appartement
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
