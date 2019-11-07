<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getAllCommentsByResolutionProjectId(int $id)
    {
        return $this->createQueryBuilder('comment')
            ->leftJoin('comment.resolutionProject', 'resolutionProject')
            ->andWhere('resolutionProject.id = :id')
            ->setParameter('id', $id)
            ->orderBy('comment.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
