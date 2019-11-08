<?php

namespace App\Controller;

use App\Entity\Resolution;
use App\Repository\CommentRepository;
use App\Repository\ResolutionProjectRepository;
use App\Repository\ResolutionRepository;
use App\Repository\VoteRepository;
use App\Service\PdfController;
use App\Service\ResolutionNumberCreator;
use App\Service\VoteVerificator;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportGeneratorController extends CRUDController
{
    private $voteRepository;
    private $voteVerificator;
    private $resolutionProjectRepository;
    private $resolutionRepository;
    private $entityManager;
    private $resolutionNumberCreator;
    private $pdfCreator;
    private $commentRepository;
    
    public function __construct(
        VoteRepository              $voteRepository,
        VoteVerificator             $voteVerificator,
        ResolutionProjectRepository $resolutionProjectRepository,
        ResolutionRepository        $resolutionRepository,
        EntityManagerInterface      $entityManager,
        ResolutionNumberCreator     $resolutionNumberCreator,
        PdfController               $pdfCreator,
        CommentRepository           $commentRepository
    ) {
        $this->voteRepository               = $voteRepository;
        $this->voteVerificator              = $voteVerificator;
        $this->resolutionProjectRepository  = $resolutionProjectRepository;
        $this->resolutionRepository         = $resolutionRepository;
        $this->entityManager                = $entityManager;
        $this->resolutionNumberCreator      = $resolutionNumberCreator;
        $this->pdfCreator                   = $pdfCreator;
        $this->commentRepository            = $commentRepository;
    }
    
    /**
     * @param string $id
     * @Route("/generate-report/{id}", name="generate_report")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generateReportAction(string $id)
    {
        $votes              = $this->voteRepository->getVotesByProjectId($id);
        $voteVerification   = $this->voteVerificator->verifyVoting($id);
        $resolutionProject  = $this->resolutionProjectRepository->findOneBy(['id' => $id]);
        $comments           = $this->commentRepository->getAllCommentsByResolutionProjectId($id);

        $this->pdfCreator->generatePdf(
            'reports/reportPdf.html.twig',
            [
                'votes'             => $votes,
                'verification'      => $voteVerification,
                'resolutionProject' => $resolutionProject,
                'comments'          => $comments
            ],
            'Raport'
        );
        return new Response('Wygenerowano raport');
    }
    
    /**
     * @Route("resolution/new/{id}", name="generate_resolution")
     */
    public function generateResolutionAction(string $id)
    {
        $resolutionProject  = $this->resolutionProjectRepository->findOneBy(['id' => $id]);
        $resolutions        = $this->resolutionRepository->findAll();
        
        $titles = [];
        
        foreach ($resolutions as $resolution) {
            $titles[] = $resolution->getTitle();
        }

        if (!in_array($resolutionProject->getTitle(), $titles)) {
            $resolution = new Resolution();
            
            $resolution->setNumber($this->resolutionNumberCreator->createNewRepositoryNumber());
            $resolution->setDate($resolutionProject->getDeadline());
            $resolution->setContent($resolutionProject->getContent());
            $resolution->setTitle($resolutionProject->getTitle());

            $this->entityManager->persist($resolution);
            $this->entityManager->flush();
        } else {
            $resolution = $this->resolutionRepository->findOneBy(['title' => $resolutionProject->getTitle()]);
        }
        
        $this->pdfCreator->generatePdf(
            'resolution/resolution.html.twig',
            [
                'resolution' => $resolution
            ],
            'Uchwała'
        );
        return new Response('Wygenerowano uchwałę');
    }
}
