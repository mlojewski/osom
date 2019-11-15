<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\OrganizationRepository;
use App\Repository\ResolutionProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentCreateController extends AbstractController
{
    
    //TODO comment controller i metoda create a nie commment create controller
    private $entityManager;
    private $resolutionProjectRepository;
    private $mailer;
    private $userRepository;
    private $organizationRepository;
    
    public function __construct(
        EntityManagerInterface      $entityManager,
        ResolutionProjectRepository $resolutionProjectRepository,
        \Swift_Mailer               $mailer,
        UserRepository              $userRepository,
        OrganizationRepository      $organizationRepository
    ) {
        $this->entityManager                = $entityManager;
        $this->resolutionProjectRepository  = $resolutionProjectRepository;
        $this->mailer                       = $mailer;
        $this->userRepository               = $userRepository;
        $this->organizationRepository       = $organizationRepository;
    }
    
    /**
     * @Route("comment/form/{projectId}/{authorId}", name="comment_form")
     * @IsGranted("ROLE_USER")
     */
    
    public function renderCommentForm($projectId, $authorId)
    {
        return $this->render(
            'comments/create_comment_form.html.twig',
            [
                'projectId' => $projectId,
                'authorId'  => $authorId
            ]
        );
    }
    
    /**
     * @Route("comment/create/{projectId}/{authorId}", name="comment_create")
     * @IsGranted("ROLE_USER")
     */
    public function createComment(Request $request)
    {
        $sentProjectId = $request->get('projectId');
        $sentAuthorId  = $request->get('authorId');
        
        $comment = new Comment();
        $comment->setCreatedAt(new \DateTime());
        $comment->setResolutionProject($this->resolutionProjectRepository->findOneBy(['id' => $sentProjectId]));
        $comment->setCommentAuthor($this->userRepository->findOneBy(['id' => $sentAuthorId]));
        $comment->setContent($request->request->get('content'));
        
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
        
        $this->sendToAll(
            $this->userRepository->getAllEmails(
                $this->resolutionProjectRepository->findOneBy(
                    ['id' => $request->request->get('projectId')]
                )->getOrganization()->getId()
            ),
            $comment,
            $request->request->get('projectId'),
            $request->request->get('authorId')
        );
        
        return $this->render('comments/confirmation.html.twig');
    }
    
    public function sendToAll(
        array $recipients,
        Comment $comment,
        $projectId,
        $authorId
    ) {
        foreach ($recipients as $recipient) {
            $message = new \Swift_Message('Nowy komentarz');
            $message->setTo($recipient);
            $message->setFrom('example@example.com');
            $message->setBody(
                $this->renderView(
                    'comments/new.html.twig',
                    [
                        'comment' => $comment,
                        'projectId' => $projectId,
                        'member' => $authorId
                    ]
                ),
                'text/html'
            );
            $this->mailer->send($message);
        }
    }
}
