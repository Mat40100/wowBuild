<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TemplateRepository")
 */
class Template
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\WowClass", inversedBy="template", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Class;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TalentThree", mappedBy="template")
     */
    private $threes;

    public function __construct()
    {
        $this->threes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClass(): ?WowClass
    {
        return $this->Class;
    }

    public function setClass(WowClass $Class): self
    {
        $this->Class = $Class;

        return $this;
    }

    /**
     * @return Collection|TalentThree[]
     */
    public function getThrees(): Collection
    {
        return $this->threes;
    }

    public function addThree(TalentThree $three): self
    {
        if (!$this->threes->contains($three)) {
            $this->threes[] = $three;
            $three->setTemplate($this);
        }

        return $this;
    }

    public function removeThree(TalentThree $three): self
    {
        if ($this->threes->contains($three)) {
            $this->threes->removeElement($three);
            // set the owning side to null (unless already changed)
            if ($three->getTemplate() === $this) {
                $three->setTemplate(null);
            }
        }

        return $this;
    }
}
