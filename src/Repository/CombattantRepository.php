<?php

namespace App\Repository;

use App\Entity\Combattant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Combattant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Combattant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Combattant[]    findAll()
 * @method Combattant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CombattantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Combattant::class);
    }

    // /**
    //  * @return Combattant[] Returns an array of Combattant objects
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
    public function findOneBySomeField($value): ?Combattant
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
