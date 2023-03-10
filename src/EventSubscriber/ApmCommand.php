<?php

namespace App\EventSubscriber;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApmCommand implements EventSubscriberInterface
{
    private $enabled;
    public function __construct()
    {
        $this->enabled = method_exists(\BlackfireProbe::class, 'startTransaction');
    }

    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        if (!$this->enabled) {
            return;
        }

        \BlackfireProbe::setTransactionName($event->getCommand()->getName());
        \BlackfireProbe::startTransaction();
    }

    public function onConsoleTerminate(ConsoleTerminateEvent $event)
    {
        if (!$this->enabled) {
            return;
        }

        \BlackfireProbe::stopTransaction();
    }

    public static function getSubscribedEvents()
    {
        return [
            ConsoleCommandEvent::class => 'onConsoleCommand',
            ConsoleTerminateEvent::class => 'onConsoleTerminate',
        ];
    }
}
