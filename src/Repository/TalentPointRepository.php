<?php

namespace App\Repository;

use App\Entity\TalentPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TalentPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method TalentPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method TalentPoint[]    findAll()
 * @method TalentPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TalentPointRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TalentPoint::class);
    }

    // /**
    //  * @return TalentPoint[] Returns an array of TalentPoint objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TalentPoint
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
