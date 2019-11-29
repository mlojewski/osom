<?php

namespace App\Entity;

use Sonata\MediaBundle\Entity\BaseGallery;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GalleryRepository")
 */
class Gallery extends BaseGallery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
}
