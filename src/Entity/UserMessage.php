<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserMessageRepository")
 */
class UserMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="writtenMessage")
     * @ORM\JoinColumn(nullable=false)
     */
    private $messageFrom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="receivedMessage")
     * @ORM\JoinColumn(nullable=false)
     */
    private $messageTo;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $writerVisible;

    /**
     * @ORM\Column(type="boolean")
     */
    private $receiverVisible;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastModificationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRead;

    public function __construct()
    {
        $this->setReceiverVisible(true);
        $this->setWriterVisible(true);
        $this->setIsRead(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageFrom(): ?User
    {
        return $this->messageFrom;
    }

    public function setMessageFrom(?User $messageFrom): self
    {
        $this->messageFrom = $messageFrom;

        return $this;
    }

    public function getMessageTo(): ?User
    {
        return $this->messageTo;
    }

    public function setMessageTo(?User $messageTo): self
    {
        $this->messageTo = $messageTo;

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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getWriterVisible(): ?bool
    {
        return $this->writerVisible;
    }

    public function setWriterVisible(bool $writerVisible): self
    {
        $this->writerVisible = $writerVisible;

        return $this;
    }

    public function getReceiverVisible(): ?bool
    {
        return $this->receiverVisible;
    }

    public function setReceiverVisible(bool $receiverVisible): self
    {
        $this->receiverVisible = $receiverVisible;

        return $this;
    }

    public function getLastModificationDate(): ?\DateTimeInterface
    {
        return $this->lastModificationDate;
    }

    public function setLastModificationDate(?\DateTimeInterface $lastModificationDate): self
    {
        $this->lastModificationDate = $lastModificationDate;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }
}
