<?php

namespace App\GitHub;

use DateTimeInterface;

class GitHubRepository
{
    private string $name;

    private string $url;

    private DateTimeInterface $updatedAt;

    public function __construct(string $fullName, string $url, DateTimeInterface $updatedAt)
    {
        $this->name = $fullName;
        $this->url = $url;
        $this->updatedAt = $updatedAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
