<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuildRepository")
 */
class Build
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
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastModificationDate;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous ne pouvez pas poster un build vide.")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez entrer un titre.")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="builds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="build")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="build", orphanRemoval=true)
     */
    private $votes;

    private $countPos;
    private $countNeg;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="WowClass", inversedBy="builds")
     * @Assert\NotBlank()
     */
    private $wowClass;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->isActive = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getLastModificationDate(): ?\DateTimeInterface
    {
        return $this->lastModificationDate;
    }

    public function setLastModificationDate(\DateTimeInterface $lastModificationDate): self
    {
        $this->lastModificationDate = $lastModificationDate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setBuild($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getBuild() === $this) {
                $comment->setBuild(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setBuild($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getBuild() === $this) {
                $vote->setBuild(null);
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getCountPos()
    {
        $this->countPos = 0;
        foreach ($this->getVotes() as $vote ) {
            if ($vote->getValue() === true) {
                $this->countPos = $this->countPos + 1;
            }
        }
        if ($this->getVotes() === null or $this->countPos === null ) {
            return 0;
        }

        return $this->countPos;
    }

    /**
     * @return int
     */
    public function getCountNeg()
    {
        $this->countNeg = 0;
        foreach ($this->getVotes() as $vote ) {
            if ($vote->getValue() === false) {
                $this->countNeg = $this->countNeg + 1;
            }
        }
        if ($this->getVotes() === null or $this->countNeg === null ) {
            return 0;
        }
        return $this->countNeg;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getWowClass(): ?WowClass
    {
        return $this->wowClass;
    }

    public function setWowClass(?WowClass $wowClass): self
    {
        $this->wowClass = $wowClass;

        return $this;
    }
}
