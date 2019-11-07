<?php

namespace App\Controller;

use App\Repository\ResolutionProjectRepository;
use App\Repository\ResolutionRepository;
use App\Service\VoteVerificator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $resolutionRepository;
    private $resolutionProjectRepository;
    private $voteVerificator;
    
    public function __construct(
        ResolutionProjectRepository $resolutionProjectRepository,
        ResolutionRepository $resolutionRepository,
        VoteVerificator $voteVerificator
    ) {
        $this->resolutionProjectRepository = $resolutionProjectRepository;
        $this->resolutionRepository = $resolutionRepository;
        $this->voteVerificator = $voteVerificator;
    }
    
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $resolutions = $this->resolutionRepository->findAll();
        $resolutionProjects = $this->resolutionProjectRepository->findAll();

        return $this->render('home/index.html.twig', [
            'resolutions' => $resolutions,
            'resolutionProjects' => $resolutionProjects
        ]);
    }
}
