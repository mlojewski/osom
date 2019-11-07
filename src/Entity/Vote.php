<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 */
class Vote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\VoteType", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $voteType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ResolutionProject", inversedBy="votes")
     */
    private $resolutionProject;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $voteAuthor;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getVoteType(): ?VoteType
    {
        return $this->voteType;
    }

    public function setVoteType(?VoteType $voteType): self
    {
        $this->voteType = $voteType;

        return $this;
    }

    public function getResolutionProject(): ?ResolutionProject
    {
        return $this->resolutionProject;
    }

    public function setResolutionProject(?ResolutionProject $resolutionProject): self
    {
        $this->resolutionProject = $resolutionProject;

        return $this;
    }

    public function getVoteAuthor(): ?User
    {
        return $this->voteAuthor;
    }

    public function setVoteAuthor(?User $voteAuthor): self
    {
        $this->voteAuthor = $voteAuthor;

        return $this;
    }
}
