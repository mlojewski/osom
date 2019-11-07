<?php

namespace App\Repository;

use App\Entity\BoardMemberFunction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BoardMemberFunction|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoardMemberFunction|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoardMemberFunction[]    findAll()
 * @method BoardMemberFunction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoardMemberFunctionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BoardMemberFunction::class);
    }
}
