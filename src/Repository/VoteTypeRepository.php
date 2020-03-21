<?php

namespace App\Repository;

use App\Entity\VoteType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VoteType|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoteType|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoteType[]    findAll()
 * @method VoteType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoteType::class);
    }

}
