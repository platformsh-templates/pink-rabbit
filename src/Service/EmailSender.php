<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;

class EmailSender
{
    public function __construct(
        private MailerInterface $mailer,
        private string $kernelRootDir,
        private string $emailSender,
        private string $noReplyAddress
    )
    {
    }

    public function sendmail(
        string          $email,
        string          $name,
        bool            $optin = false
    ): void
    {
        $message = (new TemplatedEmail())
            ->from(new Address($this->emailSender, 'Blackfire'))
            ->to(new Address(address: $email, name: $name))
            ->addReplyTo(new Address($this->noReplyAddress, 'Blackfire (No reply)'))
            ->subject('Blackfire escape game')
            ->addPart((new DataPart(fopen($this->kernelRootDir . '/assets/images/blackfire_logo.png', 'r'), 'logo', 'image/png'))->asInline())
            ->htmlTemplate('mail/landing.html.twig')
            ->textTemplate('mail/landing_text.html.twig')
            ->context([
                'name' => $name,
                'optin' => $optin,
            ]);

        $this->mailer->send($message);
    }

    public function optin(string $email, string $name, bool $optin = false): void
    {
        if (!$optin) {
            return;
        }
    }
}
