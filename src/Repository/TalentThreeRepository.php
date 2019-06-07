<?php

namespace App\Repository;

use App\Entity\TalentThree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TalentThree|null find($id, $lockMode = null, $lockVersion = null)
 * @method TalentThree|null findOneBy(array $criteria, array $orderBy = null)
 * @method TalentThree[]    findAll()
 * @method TalentThree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TalentThreeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TalentThree::class);
    }

    // /**
    //  * @return TalentThree[] Returns an array of TalentThree objects
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
    public function findOneBySomeField($value): ?TalentThree
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
