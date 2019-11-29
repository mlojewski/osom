<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganizationRepository")
 */
class Organization
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="organization")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ResolutionProject", mappedBy="organization")
     */
    private $resolutionProjects;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media", inversedBy="organizations")
     */
    private $logo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media", inversedBy="organizations")
     */
    private $footer;
    

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->resolutionProjects = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setOrganization($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getOrganization() === $this) {
                $user->setOrganization(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ResolutionProject[]
     */
    public function getResolutionProjects(): Collection
    {
        return $this->resolutionProjects;
    }

    public function addResolutionProject(ResolutionProject $resolutionProject): self
    {
        if (!$this->resolutionProjects->contains($resolutionProject)) {
            $this->resolutionProjects[] = $resolutionProject;
            $resolutionProject->setOrganization($this);
        }

        return $this;
    }

    public function removeResolutionProject(ResolutionProject $resolutionProject): self
    {
        if ($this->resolutionProjects->contains($resolutionProject)) {
            $this->resolutionProjects->removeElement($resolutionProject);
            // set the owning side to null (unless already changed)
            if ($resolutionProject->getOrganization() === $this) {
                $resolutionProject->setOrganization(null);
            }
        }

        return $this;
    }

    public function getLogo(): ?Media
    {
        return $this->logo;
    }

    public function setLogo(?Media $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getFooter(): ?Media
    {
        return $this->footer;
    }

    public function setFooter(?Media $footer): self
    {
        $this->footer = $footer;

        return $this;
    }
    
}
