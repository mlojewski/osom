<?php

namespace App\Creator;

use App\Entity\Vote;
use App\Repository\ResolutionProjectRepository;
use App\Repository\UserRepository;
use App\Repository\VoteTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class VoteCreator
{
    private $entityManager;
    private $resolutionProjectRepository;
    private $voteTypeRepository;
    private $userRepository;
    
    public function __construct(
        EntityManagerInterface      $entityManager,
        ResolutionProjectRepository $resolutionProjectRepository,
        VoteTypeRepository          $voteTypeRepository,
        UserRepository              $userRepository
    ) {
        $this->entityManager                = $entityManager;
        $this->resolutionProjectRepository  = $resolutionProjectRepository;
        $this->voteTypeRepository           = $voteTypeRepository;
        $this->userRepository               = $userRepository;
    }
    
    public function createVote(
        string $projectId,
        string $memberId,
        string $voteTypeId,
        Request $request
    ) {

        $vote = new Vote();
        $vote->setResolutionProject($this->resolutionProjectRepository->findOneBy(['id' => $projectId]));
        $vote->setVoteAuthor($this->userRepository->findOneBy(['id' => $memberId]));
        $vote->setVoteType($this->voteTypeRepository->findOneBy(['id' => $voteTypeId]));
        $vote->setTimestamp(new \DateTime());
        $vote->setIp($request->getClientIp());
        
        $this->entityManager->persist($vote);
        $this->entityManager->flush();
        return $vote;
    }
}
