<?php

namespace App\Repository;

use App\Entity\Fighter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Fighter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fighter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fighter[]    findAll()
 * @method Fighter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FighterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fighter::class);
    }

     /**
      * @return Fighter[] Returns an array of Fighter objects
      */
    public function findAllFighterAlive()
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.killed_at is null')
            ->getQuery()
            ->getResult()
            ;
    }



    public function findBattles($fighter)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT *
            FROM  battle b
            INNER JOIN battle_fighter bf 
                ON b.id = bf.battle_id 
            INNER JOIN fighter f 
                ON f.id = bf.fighter_id  
            WHERE bf.fighter_id = :idFighter';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['idFighter' => $fighter->getId()]);

        return $stmt->fetchAll();
    }


}
