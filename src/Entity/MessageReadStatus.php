<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Message;
use App\Entity\User;
use App\Entity\Commission;

#[ORM\Entity()]
class MessageReadStatus {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Message::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Message $message = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Commission::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commission $commission = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isRead = false;

    public function getCommission(): ?Commission {
        return $this->commission;
    }

    public function setCommission(?Commission $commission): self {
        $this->commission = $commission;
        return $this;
    }

    public function getMessage(): ?Message {
        return $this->message;
    }

    public function setMessage(?Message $message): self {
        $this->message = $message;
        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;
        return $this;
    }

    public function getIsRead(): bool {
        return $this->isRead;
    }
    public function setIsRead(?bool $isRead): self {
        $this->isRead = $isRead;
        return $this;
    }
}
