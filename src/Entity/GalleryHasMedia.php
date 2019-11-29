<?php

namespace App\Entity;

use Sonata\MediaBundle\Entity\BaseGalleryHasMedia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GalleryHasMediaRepository")
 */
class GalleryHasMedia extends BaseGalleryHasMedia
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
