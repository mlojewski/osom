<?php

namespace App\Service;

use App\Repository\ResolutionProjectRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private $resolutionProjectRepository;
    private $userRepository;
    private $mailerInterface;
    
    public function __construct(
        ResolutionProjectRepository $resolutionProjectRepository,
        UserRepository              $userRepository,
        MailerInterface             $mailerInterface
    ) {
        $this->resolutionProjectRepository  = $resolutionProjectRepository;
        $this->userRepository               = $userRepository;
        $this->mailerInterface              = $mailerInterface;
    }
    
    public function sendToRecipients(string $id, int $organizationId, string $targetGroup)
    {
        $project = $this->resolutionProjectRepository->findOneBy(['id' => $id]);
        $members = $this->userRepository->getAllEmails($organizationId, $targetGroup);
        
        foreach ($members as $member) {
            $message = (new TemplatedEmail())
                ->from('kontakt@easyvote.pl')
                ->to($member)
                ->subject('Nowy projekt uchwały')
                ->htmlTemplate('emails/new_project_email.html.twig')
                ->context(
                        [
                            'projectId' => $id,
                            'project'   => $project,
                            'member'    => $this->userRepository->findOneBy(['email' => $member])->getId(),
                        ]
                    )
                ;
            
            if ($project->getIsPublished() === true) {
                $this->mailerInterface->send($message);
            }
        }
    }
    
    public function sendPasswordReset(string $email, string $token)
    {
        $message = (new TemplatedEmail())
            ->from('kontakt@easyvote.pl')
            ->to($email)
            ->subject('Reset hasła w portalu easyvote')
            ->htmlTemplate('emails/password_reset.html.twig')
            ->context(['token' => $token]);

        $this->mailerInterface->send($message);
    }
}