<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "`user`")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $username = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: "owner", targetEntity: BigFootSighting::class)]
    private Collection $bigFootSightings;

    #[ORM\OneToMany(mappedBy: "owner", targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $agreedToTermsAt;

    public function __construct()
    {
        $this->bigFootSightings = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // We're using bcrypt in security.yaml to encode the password, so
        // the salt value is built-in and you don't have to generate one
        // See https://en.wikipedia.org/wiki/Bcrypt

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection|BigFootSighting[]
     */
    public function getBigFootSightings(): Collection
    {
        return $this->bigFootSightings;
    }

    public function addBigFootSighting(BigFootSighting $bigFootSighting): self
    {
        if (!$this->bigFootSightings->contains($bigFootSighting)) {
            $this->bigFootSightings[] = $bigFootSighting;
            $bigFootSighting->setOwner($this);
        }

        return $this;
    }

    public function removeBigFootSighting(BigFootSighting $bigFootSighting): self
    {
        if ($this->bigFootSightings->contains($bigFootSighting)) {
            $this->bigFootSightings->removeElement($bigFootSighting);
            // set the owning side to null (unless already changed)
            if ($bigFootSighting->getOwner() === $this) {
                $bigFootSighting->setOwner(null);
            }
        }

        return $this;
    }

    public function getAvatarUrl(): string
    {
        return sprintf('https://avatars.dicebear.com/4.5/api/human/%s.svg?mood[]=happy', $this->getEmail());
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getAgreedToTermsAt(): ?\DateTimeInterface
    {
        return $this->agreedToTermsAt;
    }

    public function setAgreedToTermsAt(\DateTimeInterface $agreedToTermsAt): self
    {
        $this->agreedToTermsAt = $agreedToTermsAt;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function __serialize(): array
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        return [$this->id, $this->username, $this->password];
    }

    /**
     * @param array{int|null, string, string} $data
     */
    public function __unserialize(array $data): void
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        [$this->id, $this->username, $this->password] = $data;
    }
}
