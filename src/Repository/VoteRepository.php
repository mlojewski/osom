<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function getVote(int $resolutionProjectId, int $memberId)
    {
        return $this->createQueryBuilder('vote')
            ->andWhere('vote.voteAuthor = :member')
            ->andWhere('vote.resolutionProject = :project')
            ->setParameter('project', $resolutionProjectId)
            ->setParameter('member', $memberId)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function getVotesByProjectId(string $projectId)
    {
        return $this->createQueryBuilder('vote')
            ->leftJoin('vote.resolutionProject', 'resolutionProject')
            ->andWhere('resolutionProject.id = :projectId')
            ->setParameter('projectId', $projectId)
            ->getQuery()
            ->getResult();
    }
}
