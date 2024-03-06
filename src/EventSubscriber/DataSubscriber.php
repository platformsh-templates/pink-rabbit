<?php

namespace App\EventSubscriber;

use Platformsh\DevRelBIPhpSdk\Symfony\EventSubscriber\DataEventSubscriber;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class DataSubscriber extends DataEventSubscriber
{
    protected function logEvents(RequestEvent $event): void
    {
        $isLoggedIn = $this->isLoggedIn();
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        switch (true) {
            case '/landing' === $path:
                $this->log('landing');
                break;
            case '/' === $path && !$isLoggedIn:
                $this->log('step1');
                break;
            case '/eat-me' === $path:
                $this->log('step2');
                break;
            case '/sighting/666' === $path:
                $this->log('step3');
                break;
            case '/login' === $path:
                $this->log('step3');
                break;
            case '/' === $path && $isLoggedIn:
                $this->log('final');
                break;
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
