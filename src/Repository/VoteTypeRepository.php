<?php

namespace App\Repository;

use App\Entity\VoteType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VoteType|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoteType|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoteType[]    findAll()
 * @method VoteType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VoteType::class);
    }

    // /**
    //  * @return VoteType[] Returns an array of VoteType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VoteType
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
