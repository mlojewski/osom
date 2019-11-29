<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResolutionRepository")
 */
class Resolution
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $number;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=550)
     */
    private $title;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ResolutionProject", mappedBy="resolution", cascade={"persist", "remove"})
     */
    private $resolutionProject;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getResolutionProject(): ?ResolutionProject
    {
        return $this->resolutionProject;
    }

    public function setResolutionProject(?ResolutionProject $resolutionProject): self
    {
        $this->resolutionProject = $resolutionProject;

        // set (or unset) the owning side of the relation if necessary
        $newResolution = null === $resolutionProject ? null : $this;
        if ($resolutionProject->getResolution() !== $newResolution) {
            $resolutionProject->setResolution($newResolution);
        }

        return $this;
    }
}
