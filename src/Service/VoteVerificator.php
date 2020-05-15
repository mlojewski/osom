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
        UserRepository              $userRepository
    ) {
        $this->userRepository               = $userRepository;
        $this->resolutionProjectRepository  = $resolutionProjectRepository;
    }
    
    public function verifyVoting(string $resolutionProjectId)
    {
        $resolutionProject = $this->resolutionProjectRepository->findOneBy(['id' => $resolutionProjectId]);
        
        $votes = $resolutionProject->getVotes();

        if ($this->checkValidity($votes, $resolutionProject) == true) {
            $voteResult = 0;
            foreach ($votes as $vote) {
                if ($vote->getVoteType()->getValue() === 1) {
                    $voteResult+=$this->checkVoteAuthor($vote);
                } elseif ($vote->getVoteType()->getValue() === -1) {
                    $voteResult-=$this->checkVoteAuthor($vote);
                };
            }
            return $voteResult;
        } else {
            return null;
        }
    }
    
    public function checkValidity($votes, $resolutionProject)
    {

        $projectId = $resolutionProject->getOrganization()->getId();
        $targeGroup = $resolutionProject->getTargetGroup();
        if ($votes->count() > $this->userRepository->countAllOrganizationMembers($projectId, $targeGroup) / 2) {
            return true;
        }
        return false;
    }
    
    public function checkVoteAuthor($vote): float
    {
        if ($vote->getVoteAuthor()->getIsDecider() === true) {
            return 1.1;
        }
        return 1;
    }
}
