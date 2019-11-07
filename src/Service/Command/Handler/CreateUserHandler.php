<?php

namespace App\Service\Command\Handler;

use App\Entity\User;
use App\Service\Command\CreateUserCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserHandler
{
    private $entityManager;
    private $encoder;
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->entityManager = $entityManager;
        $this->encoder       = $encoder;
    }
    
    public function handle(CreateUserCommand $command): void
    {
        $user = new User();
        $encoded = $this->encoder->encodePassword($user, $command->getPassword());
        $user
            ->setEmail($command->getEmail())
            ->addRole($command->getRole())
            ->setPassword($encoded)
            ->setName($command->getName())
            ->setLastName($command->getLastName());
            
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
