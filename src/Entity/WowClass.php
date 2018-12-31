<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WowCLassRepository")
 */
class WowClass
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
    private $img;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Build", mappedBy="wowClass")
     */
    private $builds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="wowClass")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\NotBlank()
     */
    private $fontColor;

    public function __construct()
    {
        $this->builds = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|Build[]
     */
    public function getBuilds(): Collection
    {
        return $this->builds;
    }

    public function addBuild(Build $build): self
    {
        if (!$this->builds->contains($build)) {
            $this->builds[] = $build;
            $build->setWowClass($this);
        }

        return $this;
    }

    public function removeBuild(Build $build): self
    {
        if ($this->builds->contains($build)) {
            $this->builds->removeElement($build);
            // set the owning side to null (unless already changed)
            if ($build->getWowClass() === $this) {
                $build->setWowClass(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setWowClass($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getWowClass() === $this) {
                $user->setWowClass(null);
            }
        }

        return $this;
    }

    public function getFontColor(): ?string
    {
        return $this->fontColor;
    }

    public function setFontColor(?string $fontColor): self
    {
        $this->fontColor = $fontColor;

        return $this;
    }
}
