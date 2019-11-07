<?php

namespace App\Service;

use App\Repository\ResolutionProjectRepository;
use App\Repository\UserRepository;

class VoteVerificator
{
    private $userRepository;
    private $resolutionProjectRepository;
    public function __construct(
        ResolutionProjectRepository $resolutionProjectRepository,
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
        $this->resolutionProjectRepository = $resolutionProjectRepository;
    }
    
    public function verifyVoting(string $resolutionProjectId)
    {
        $resolutionProject = $this->resolutionProjectRepository->findOneBy(['id' => $resolutionProjectId]);
        
        $votes = $resolutionProject->getVotes();

        if ($this->checkValidity($votes) == true) {
            $voteResult = 0;
            foreach ($votes as $vote) {
                if ($vote->getVoteType()->getType() === "Za") {
                    $voteResult+=$this->checkVoteAuthor($vote);
                } elseif ($vote->getVoteType()->getType() === "Przeciw") {
                    $voteResult-=$this->checkVoteAuthor($vote);
                };
            }
            return $voteResult;
        } else {
            return null;
        }
    }
    
    public function checkValidity($votes)
    {
        if ($votes->count() > count($this->userRepository->findAll()) / 2) {
            return true;
        }
        return false;
    }
    
    public function checkVoteAuthor($vote): float
    {
        if ($vote->getVoteAuthor()->getFunction()->getId() === 1) {
            return 1.1;
        }
        return 1;
    }
}
