<?php

namespace App\Repository;

use App\Entity\Firstname;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Firstname|null find($id, $lockMode = null, $lockVersion = null)
 * @method Firstname|null findOneBy(array $criteria, array $orderBy = null)
 * @method Firstname[]    findAll()
 * @method Firstname[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FirstnameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Firstname::class);
    }

    // /**
    //  * @return Firstname[] Returns an array of Firstname objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Firstname
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
