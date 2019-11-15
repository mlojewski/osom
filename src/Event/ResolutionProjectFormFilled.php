<?php

namespace App\Event;

use App\Entity\ResolutionProject;
use Symfony\Contracts\EventDispatcher\Event;

class ResolutionProjectFormFilled extends Event
{
    const NAME = 'ResolutionProjectFormFilled';
    /**
     * @var ResolutionProject
     */
    private $resolutionProject;
    
    private $id;
    
    public function __construct($id, ResolutionProject $resolutionProject)
    {
        $this->resolutionProject = $resolutionProject;
        $this->id = $id;
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return ResolutionProject
     */
    public function getResolutionProject(): ResolutionProject
    {
        return $this->resolutionProject;
    }
    
    
}
