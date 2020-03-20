<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordResettingController extends AbstractController
{
 
    private $userRepository;
    
    private $mailer;
    
    private $entityManager;
    
    private $passwordEncoder;
    
    public function __construct(
        UserRepository $userRepository,
        Mailer $mailer,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }
    
    /**
     * @Route("/resetting", name="password_resetting")
     */
    public function index(Request $request)
    {
        $user = $this->userRepository->findOneBy(['email' => $request->request->get('email')]);
        if ($user) {
            $token = bin2hex(random_bytes(20));
            $user->setToken($token);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->mailer->sendPasswordReset($user->getEmail(), $token);
            $message = 'Wiadomość z instrukcją resetowania hasła została wysłana na podany email';
        } else {
            $message = 'Nie znaleziono użytkownika o podanym emailu';
        }
        return $this->render('security/resetting-form.html.twig', [
            'message' => $message
        ]);
    }
    
    /**
     * @Route("/change-password/{token}", name="change_password")
     */
    public function changePasswordForm(Request $request)
    {
        $user = $this->userRepository->findOneBy(['token' => $request->get('token')]);
        
        $user->setToken(null);
        if ($user) {
            return $this->render('security/new-password-form.html.twig', ['id' => $user->getId()]);
        } else {
            return $this->redirect('login');
        }
    }
    
    /**
     * @Route ("new-password", name="new_password")
     */
    public function newPassword(Request $request)
    {
        $user = $this->userRepository->findOneBy(['id' => $request->get('userId')]);
        
        if ($user && $request->get('firstPassword') === $request->get('secondPassword')) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $request->get('firstPassword')
                )
            );
            $user->setToken('null');
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->render('security/resetting-form.html.twig', ['message' => 'Reset hasła przebiegł pomyślnie, możesz się zalogować']);
        } else {
            return $this->render('security/resetting-form.html.twig', ['message' => 'coś poszło nie tak, spróbuj ponownie']);
        }
    }
}
