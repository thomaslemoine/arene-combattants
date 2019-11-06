<?php

namespace App\Repository;

use App\Entity\CombattantController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CombattantController|null find($id, $lockMode = null, $lockVersion = null)
 * @method CombattantController|null findOneBy(array $criteria, array $orderBy = null)
 * @method CombattantController[]    findAll()
 * @method CombattantController[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CombattantControllerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CombattantController::class);
    }

    // /**
    //  * @return CombattantController[] Returns an array of CombattantController objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CombattantController
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
