<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
#[ORM\Table(name: "comment")]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne(targetEntity: PinkRabbit::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PinkRabbit $pinkRabbit = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getPinkRabbit(): ?PinkRabbit
    {
        return $this->pinkRabbit;
    }

    public function setPinkRabbit(?PinkRabbit $pinkRabbit): self
    {
        $this->pinkRabbit = $pinkRabbit;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
