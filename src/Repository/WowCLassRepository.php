<?php

namespace App\Repository;

use App\Entity\WowClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WowClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method WowClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method WowClass[]    findAll()
 * @method WowClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WowCLassRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WowClass::class);
    }

    // /**
    //  * @return WowClass[] Returns an array of WowClass objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WowClass
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
