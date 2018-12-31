<?php

namespace App\Repository;

use App\Entity\Build;
use App\Entity\WowClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Build|null find($id, $lockMode = null, $lockVersion = null)
 * @method Build|null findOneBy(array $criteria, array $orderBy = null)
 * @method Build[]    findAll()
 * @method Build[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuildRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Build::class);
    }

    /**
     * @param int $number
     * @return mixed
     */
    public function findLast(int $number = 10)
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.creationDate', 'DESC')
            ->where('b.isActive = true')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param WowClass $wowClass
     * @return mixed
     */
    public function findByClass(WowClass $wowClass)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.wowClass', 'b')
                ->where('b = :wowClass')
                ->andWhere('a.isActive = true')
                ->setParameters(['wowClass' => $wowClass])
            ->getQuery()
            ->getResult()
            ;
    }
}
