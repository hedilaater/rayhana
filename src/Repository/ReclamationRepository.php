<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    //    /**
    //     * @return Reclamation[] Returns an array of Reclamation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reclamation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function countReclamationsByDate(): array
    {
        $results = $this->createQueryBuilder('r')
            ->select("r.date_rec as fullDate, COUNT(r.id) as count")
            ->groupBy('fullDate')
            ->orderBy('fullDate', 'ASC')
            ->getQuery()
            ->getResult();
        
        foreach ($results as &$result) {
            $result['date'] = $result['fullDate']->format('Y-m-d');
            unset($result['fullDate']);
        }
    
        return $results;
    }

    public function countReclamationsByEtat(): array
    {
        return $this->createQueryBuilder('r')
            ->select("r.etat as etat, COUNT(r.id) as count")
            ->groupBy('r.etat')
            ->getQuery()
            ->getResult();
    }
}
