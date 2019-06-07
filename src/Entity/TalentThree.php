<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TalentThreeRepository")
 */
class TalentThree
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
     * @ORM\OneToMany(targetEntity="App\Entity\TalentPoint", mappedBy="three", orphanRemoval=true)
     */
    private $talentPoints;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Template", inversedBy="threes")
     */
    private $template;

    public function __construct()
    {
        $this->talentPoints = new ArrayCollection();
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

    /**
     * @return Collection|TalentPoint[]
     */
    public function getTalentPoints(): Collection
    {
        return $this->talentPoints;
    }

    public function addTalentPoint(TalentPoint $talentPoint): self
    {
        if (!$this->talentPoints->contains($talentPoint)) {
            $this->talentPoints[] = $talentPoint;
            $talentPoint->setThree($this);
        }

        return $this;
    }

    public function removeTalentPoint(TalentPoint $talentPoint): self
    {
        if ($this->talentPoints->contains($talentPoint)) {
            $this->talentPoints->removeElement($talentPoint);
            // set the owning side to null (unless already changed)
            if ($talentPoint->getThree() === $this) {
                $talentPoint->setThree(null);
            }
        }

        return $this;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
    }
}
