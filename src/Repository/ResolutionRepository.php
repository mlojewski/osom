<?php

namespace App\Repository;

use App\Entity\Resolution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Resolution|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resolution|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resolution[]    findAll()
 * @method Resolution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResolutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resolution::class);
    }

    public function getLatestResolution(): array
    {
        return $this->createQueryBuilder('resolution')
            ->orderBy('resolution.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
