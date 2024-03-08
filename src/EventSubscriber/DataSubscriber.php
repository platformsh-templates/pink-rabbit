<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Platformsh\DevRelBIPhpSdk\Symfony\EventSubscriber\DataEventSubscriber;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class DataSubscriber extends DataEventSubscriber
{
    protected function logEvents(RequestEvent $event): void
    {
        /**
         * Will you cheat and find a shortcut to the last step?
         * Well, you can't. But congrats on exploring up there. Smart move!
         * We are logging key actions to know how many people reach each step.
         * So, nothing interesting for you is happening here.
         */

        $request = $event->getRequest();
        $path = $request->getPathInfo();
        $isLoggedIn = $this->isLoggedIn();
        $isProfiling = array_key_exists('HTTP_X_BLACKFIRE_QUERY', $_SERVER);

        $key = $path . "|" . (int) $isLoggedIn . "|" . (int) $isProfiling;
        $mapping = json_decode(urldecode($_ENV['ACTION_LOGGING_MAP'] ?? ''), JSON_OBJECT_AS_ARRAY);
        $event = $mapping[$key] ?? null;
        if ($event) {
            $this->log($event);
        }
    }

    protected function getUserId(RequestEvent $event): ?string
    {
        return null;
    }

    protected function getSharedData(RequestEvent $event): array
    {
        return [
            'is_logged_in' => $this->isLoggedIn(),
            'conference' => $_ENV['EVENT_NAME'] ?? '',
        ];
    }

    private function isLoggedIn(): bool
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            return true;
        }

        return false;
    }
}
