<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry ;
use \DateTime;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * @return Evenement[] Returns an array of Evenement objects
     */
    
    public function findByType($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.type = :val')
            ->setParameter('val', $value)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * @return Evenement[] Returns an array of Evenement objects
     */
    public function findToCome()
    {
        $date = new DateTime();
        return $this->createQueryBuilder('e')
            ->andWhere('e.date > :date')
            ->setParameter('date', $date)
            ->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Evenement[] Returns an array of Evenement objects
     */
    public function findByTypeToCome($value)
    {
        $date = new DateTime();
        return $this->createQueryBuilder('e')
            ->andWhere('e.type = :val')
            ->andWhere('e.date > :date')
            ->setParameter('val', $value)
            ->setParameter('date', $date)
            ->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Evenement[] Returns an array of Evenement objects
     */
    public function findHasHappened()
    {
        $date = new DateTime();
        return $this->createQueryBuilder('e')
            ->andWhere('e.date <= :date')
            ->setParameter('date', $date)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Evenement[] Returns an array of Evenement objects
     */
    public function findHasHappenedAndToCome()
    {
        
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Evenement[] Returns an array of Evenement objects
     */
    public function findAllHasHappenedAndToCome()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Evenement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
