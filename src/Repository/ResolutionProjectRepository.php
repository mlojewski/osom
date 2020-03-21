<?php

namespace App\Repository;

use App\Entity\ResolutionProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ResolutionProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResolutionProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResolutionProject[]    findAll()
 * @method ResolutionProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResolutionProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResolutionProject::class);
    }
}
