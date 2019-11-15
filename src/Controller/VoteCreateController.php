<?php

namespace App\Controller;

use App\Creator\VoteCreator;
use App\Repository\ResolutionProjectRepository;
use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VoteCreateController extends AbstractController
{
    private $voteRepository;
    private $voteCreator;
    private $resolutionProjectRepository;
    
    public function __construct(
        VoteRepository              $voteRepository,
        VoteCreator                 $voteCreator,
        ResolutionProjectRepository $resolutionProjectRepository
    ) {
        $this->voteRepository               = $voteRepository;
        $this->voteCreator                  = $voteCreator;
        $this->resolutionProjectRepository  = $resolutionProjectRepository;
    }
    
    /**
     * @Route("/vote/create/{projectId}/{memberId}/{voteTypeId}", name="vote_create")
     */
    public function createVote(
        string $projectId,
        string $memberId,
        string $voteTypeId,
        Request $request
    ) {
        $resolutionProject = $this->resolutionProjectRepository->findOneBy(['id' => $projectId]);
        
        $now = new \DateTime();

        if ($this->voteRepository->getVote($projectId, $memberId) === null) {
            if ($resolutionProject->getDeadline() > $now) {
                $vote = $this->voteCreator->createVote($projectId, $memberId, $voteTypeId, $request);
                $new  = true;
            } else {
                $vote = null;
                $new  = false;
            }
        } else {
            $vote = $this->voteRepository->getVote($projectId, $memberId);
            $new  = false;
        }

        return $this->render(
            'vote_create/create_vote.html.twig',
            [
            'vote'               => $vote,
            'new'                => $new,
            'resolutionProject'  => $resolutionProject
            ]
        );
    }
}
