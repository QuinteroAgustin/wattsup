<?php

namespace App\Entity;

use App\Repository\CommissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: CommissionRepository::class)]
#[Broadcast]
class Commission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'authors')]
    private ?User $author = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $closedAt = null;

    #[ORM\Column]
    private ?bool $isClosed = null;

    #[ORM\Column]
    private ?bool $isTemp = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'notification')]
    private Collection $notification;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'commission')]
    private Collection $messages;

    public function __construct()
    {
        $this->notification = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getClosedAt(): ?\DateTimeInterface
    {
        return $this->closedAt;
    }

    public function setClosedAt(?\DateTimeInterface $closedAt): static
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    public function isClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setClosed(bool $isClosed): static
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    public function isTemp(): ?bool
    {
        return $this->isTemp;
    }

    public function setTemp(bool $isTemp): static
    {
        $this->isTemp = $isTemp;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getNotification(): Collection
    {
        return $this->notification;
    }

    public function addNotification(User $notification): static
    {
        if (!$this->notification->contains($notification)) {
            $this->notification->add($notification);
        }

        return $this;
    }

    public function removeNotification(User $notification): static
    {
        $this->notification->removeElement($notification);

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setCommission($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getCommission() === $this) {
                $message->setCommission(null);
            }
        }

        return $this;
    }
}
