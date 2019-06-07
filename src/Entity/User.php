<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email", message="Cet email à déjà été utilisé.")
 * @UniqueEntity("username", message="Ce nom d'utilisateur est déjà employé.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Build", mappedBy="author")
     */
    private $builds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="author", orphanRemoval=true)
     */
    private $votes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WowClass", inversedBy="users")
     */
    private $wowClass;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Build", inversedBy="followers")
     */
    private $favorites;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetPasswordToken;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserMessage", mappedBy="messageFrom", orphanRemoval=true)
     */
    private $writtenMessage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserMessage", mappedBy="messageTo", orphanRemoval=true)
     */
    private $receivedMessage;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $apiState;

    public function __construct()
    {
        $this->builds = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->isActive = true;
        $this->favorites = new ArrayCollection();
        $this->writtenMessage = new ArrayCollection();
        $this->receivedMessage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        if ($this->id === null ) return '0';
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $build->setAuthor($this);
        }

        return $this;
    }

    public function removeBuild(Build $build): self
    {
        if ($this->builds->contains($build)) {
            $this->builds->removeElement($build);
            // set the owning side to null (unless already changed)
            if ($build->getAuthor() === $this) {
                $build->setAuthor(null);
            }
        }

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
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
            $vote->setAuthor($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getAuthor() === $this) {
                $vote->setAuthor(null);
            }
        }

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Build[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Build $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(Build $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
        }

        return $this;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): self
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    /**
     * @return Collection|UserMessage[]
     */
    public function getWrittenMessage(): Collection
    {
        return $this->writtenMessage;
    }

    public function addWrittenMessage(UserMessage $writtenMessage): self
    {
        if (!$this->writtenMessage->contains($writtenMessage)) {
            $this->writtenMessage[] = $writtenMessage;
            $writtenMessage->setMessageFrom($this);
        }

        return $this;
    }

    public function removeWrittenMessage(UserMessage $writtenMessage): self
    {
        if ($this->writtenMessage->contains($writtenMessage)) {
            $this->writtenMessage->removeElement($writtenMessage);
            // set the owning side to null (unless already changed)
            if ($writtenMessage->getMessageFrom() === $this) {
                $writtenMessage->setMessageFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserMessage[]
     */
    public function getReceivedMessage(): Collection
    {
        return $this->receivedMessage;
    }

    public function addReceivedMessage(UserMessage $receivedMessage): self
    {
        if (!$this->receivedMessage->contains($receivedMessage)) {
            $this->receivedMessage[] = $receivedMessage;
            $receivedMessage->setMessageTo($this);
        }

        return $this;
    }

    public function removeReceivedMessage(UserMessage $receivedMessage): self
    {
        if ($this->receivedMessage->contains($receivedMessage)) {
            $this->receivedMessage->removeElement($receivedMessage);
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getMessageTo() === $this) {
                $receivedMessage->setMessageTo(null);
            }
        }

        return $this;
    }

    public function getApiState(): ?string
    {
        return $this->apiState;
    }

    public function setApiState(?string $apiState): self
    {
        $this->apiState = $apiState;

        return $this;
    }
}
