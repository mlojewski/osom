<?php

namespace App\Controller;

use App\Coder\ParameterCoder;
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
    private $coder;
    private $voteRepository;
    private $voteVerificator;
    private $userRepository;
    
    public function __construct(
        \Swift_Mailer $mailer,
        ResolutionProjectRepository $resolutionProjectRepository,
        ParameterCoder $coder,
        VoteRepository $voteRepository,
        VoteVerificator $voteVerificator,
        UserRepository $userRepository
    ) {
        $this->mailer = $mailer;
        $this->resolutionProjectRepository = $resolutionProjectRepository;
        $this->coder = $coder;
        $this->voteRepository = $voteRepository;
        $this->voteVerificator = $voteVerificator;
        $this->userRepository = $userRepository;
    }
    
    /**
     * @Route("/resolutionproject/{slug}", name="resolution_project")
     */
    public function resolutionProjectViewer(string $slug)
    {
        $resolutionProject = $this->resolutionProjectRepository->findOneBy(['number' => $slug]);
        
        $date = date_format($resolutionProject->getDate(), 'Y:m:d h:i:s');

        return $this->render('resolution_project/index.html.twig', [
            'resolutionProject' => $resolutionProject,
            'date' => $date,
        ]);
    }
    
    public function sendToRecipients(string $id)
    {
        $project = $this->resolutionProjectRepository->findOneBy(['id' => $id]);
        $members = $this->userRepository->getAllEmails();

        foreach ($members as $member) {
            $message = (new \Swift_Message('Nowy projekt uchwały'))
                ->setFrom('example@example.com')
                ->setTo($member)
                ->setBody(
                    $this->renderView(
                        'emails/new_project_email.html.twig',
                        [
                            'projectId' => $this->coder->encode($id),
                            'project' => $project,
                            'member' => $this->coder->encode(
                                $this->userRepository->findOneBy(['email' => $member])->getId()
                            )
                        ]
                    ),
                    'text/html'
                );
    
            if ($project->getIsPublished() === true) {
                $this->mailer->send($message);
            }
        }
    }
}
