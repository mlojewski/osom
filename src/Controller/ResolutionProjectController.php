<?php

namespace App\Controller;

use App\Repository\ResolutionProjectRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use App\Service\VoteVerificator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ResolutionProjectController extends AbstractController
{
    private $resolutionProjectRepository;
    private $mailer;
    private $voteRepository;
    private $voteVerificator;
    private $userRepository;
    
    public function __construct(
        \Swift_Mailer               $mailer,
        ResolutionProjectRepository $resolutionProjectRepository,
        VoteRepository              $voteRepository,
        VoteVerificator             $voteVerificator,
        UserRepository              $userRepository
    ) {
        $this->mailer                       = $mailer;
        $this->resolutionProjectRepository  = $resolutionProjectRepository;
        $this->voteRepository               = $voteRepository;
        $this->voteVerificator              = $voteVerificator;
        $this->userRepository               = $userRepository;
    }
    
    /**
     * @Route("/resolutionproject/{slug}", name="resolution_project")
     */
    public function resolutionProjectViewer(string $slug)
    {
        $resolutionProject = $this->resolutionProjectRepository->findOneBy(['number' => $slug]);
        
        $date = date_format($resolutionProject->getDate(), 'Y:m:d h:i:s');

        return $this->render(
            'resolution_project/index.html.twig',
            [
                'resolutionProject' => $resolutionProject,
                'date'              => $date,
            ]
        );
    }
}
