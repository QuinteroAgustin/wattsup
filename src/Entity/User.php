<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The User roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    /**
     * @var string|null
     */
    private $plainPassword;

    /**
     * @var Collection<int, ForgetPassword>
     */
    #[ORM\OneToMany(targetEntity: ForgetPassword::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $forgetPasswords;

    /**
     * @var Collection<int, Commission>
     */
    #[ORM\OneToMany(targetEntity: Commission::class, mappedBy: 'author')]
    private Collection $authors;

    /**
     * @var Collection<int, Commission>
     */
    #[ORM\ManyToMany(targetEntity: Commission::class, mappedBy: 'notification')]
    private Collection $notification;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'user')]
    private Collection $messages;

    public function __construct()
    {
        $this->forgetPasswords = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->notification = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this User.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every User at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, ForgetPassword>
     */
    public function getForgetPasswords(): Collection
    {
        return $this->forgetPasswords;
    }

    public function addForgetPassword(ForgetPassword $forgetPassword): static
    {
        if (!$this->forgetPasswords->contains($forgetPassword)) {
            $this->forgetPasswords->add($forgetPassword);
            $forgetPassword->setUser($this);
        }

        return $this;
    }

    public function removeForgetPassword(ForgetPassword $forgetPassword): static
    {
        if ($this->forgetPasswords->removeElement($forgetPassword)) {
            // set the owning side to null (unless already changed)
            if ($forgetPassword->getUser() === $this) {
                $forgetPassword->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commission>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Commission $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->setAuthor($this);
        }

        return $this;
    }

    public function removeAuthor(Commission $author): static
    {
        if ($this->authors->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getAuthor() === $this) {
                $author->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commission>
     */
    public function getNotification(): Collection
    {
        return $this->notification;
    }

    public function addNotification(Commission $notification): static
    {
        if (!$this->notification->contains($notification)) {
            $this->notification->add($notification);
            $notification->addNotification($this);
        }

        return $this;
    }

    public function removeNotification(Commission $notification): static
    {
        if ($this->notification->removeElement($notification)) {
            $notification->removeNotification($this);
        }

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
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }
    
}
