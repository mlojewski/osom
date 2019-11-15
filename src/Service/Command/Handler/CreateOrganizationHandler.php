<?php

namespace App\Service\Command\Handler;

use App\Entity\Organization;
use App\Service\Command\CreateOrganizationCommand;
use Doctrine\ORM\EntityManagerInterface;


class CreateOrganizationHandler
{
    private $entityManager;
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }
    
    public function handle(CreateOrganizationCommand $command): void
    {
        $organization = new Organization();
        $organization
            ->setEmail($command->getEmail())
            ->setName($command->getName());
            
        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }
}
