<?php

namespace App\Twig;

use App\Entity\User;
use App\Service\CommentHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private CommentHelper $commentHelper
    ) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('user_activity_text', [$this, 'getUserActivityText']),
        ];
    }

    public function getUserActivityText(User $user): string
    {
        if ('Chessy' === $user->getUsername()) {
            return 'floating smile';
        }

        $commentCount = $this->commentHelper->countRecentCommentsForUser($user);

        if ($commentCount > 50) {
            return 'pink rabbit fanatic';
        }

        if ($commentCount > 30) {
            return 'believer';
        }

        if ($commentCount > 20) {
            return 'hobbyist';
        }

        return 'skeptic';
    }
}
