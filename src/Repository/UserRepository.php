<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    
    public function getAllEmails(int $organizationId, string $targetGroup): array
    {
        $users = $this->findBy(['organization' => $organizationId]);
        $board = [];
        
        foreach ($users as $user) {
            if (in_array('ROLE_BOARD',$user->getRoles())) {
                $board[] = $user->getId();
            }
        }

        if ($targetGroup === 'ROLE_BOARD') {

            $resultArr = $this->createQueryBuilder('user')
                              ->select('user.email')
                              ->leftJoin('user.organization', 'organization')
                              ->andWhere('organization.id = :id')
                              ->andWhere('user.id IN (:board)')
                              ->setParameter('id', $organizationId)
                              ->setParameter('board', $board)
                              ->getQuery()
                              ->getResult();
        } else {
            $resultArr = $this->createQueryBuilder('user')
                              ->select('user.email')
                              ->leftJoin('user.organization', 'organization')
                              ->andWhere('organization.id = :id')
                              ->setParameter('id', $organizationId)
                              ->getQuery()
                              ->getResult();
        }
        
        $result = [];
        foreach ($resultArr as $item) {
            foreach ($item as $email) {
                $result[] = $email;
            }
        }
        return $result;
    }
    
    public function countAllOrganizationMembers(int $organizationId, string $role): int
    {
        $users = $this->findBy(['organization' => $organizationId]);
        $board = [];
    
        foreach ($users as $user) {
            if (in_array('ROLE_BOARD',$user->getRoles())) {
                $board[] = $user->getId();
            }
        }
        
        if ($role == "ROLE_BOARD") {
            return $this->createQueryBuilder('user')
                        ->select('count(user)')
                        ->leftJoin('user.organization', 'organization')
                        ->andWhere('organization.id = :id')
                        ->andWhere('user.id IN (:board)')
                        ->setParameter('id', $organizationId)
                        ->setParameter('board', $board)
                        ->getQuery()
                        ->getSingleScalarResult();
            
        } else {
            return $this->createQueryBuilder('user')
                        ->select('count(user)')
                        ->leftJoin('user.organization', 'organization')
                        ->andWhere('organization.id = :id')
                        ->setParameter('id', $organizationId)
                        ->getQuery()
                        ->getSingleScalarResult();
        }
        
       
    }
}
