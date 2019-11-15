<?php

namespace App\Listener;

use App\Event\ResolutionProjectFormFilled;
use App\Repository\OrganizationRepository;

class ResolutionProjectFormFilledListener
{
    private $organizationRepository;
    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }
    
    public function onFormFilledAction(ResolutionProjectFormFilled $resolutionProjectFormFilled)
    {

        $project = $resolutionProjectFormFilled->getResolutionProject();
        
        $organization = $this->organizationRepository->findOneBy(['id' => $resolutionProjectFormFilled->getId()]);

        $project->setOrganization($organization);
    }
}