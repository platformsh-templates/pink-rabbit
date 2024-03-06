<?php

namespace App\Controller;

use App\Form\LandingPageFormType;
use Platformsh\DevRelBIPhpSdk\Symfony\DataLogger;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends MainController
{
    #[Route('/landing', name: 'landing_page')]
    public function landing(
        Request $request,
        FormFactoryInterface $formFactory,
        MailerInterface $mailer,
        DataLogger $dataLogger
    ): Response {
        $form = $formFactory->createNamed('', LandingPageFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = $data['email'] ?? null;
            $name = $data['name'] ?? null;
            $optin = $data['optin'] ?? false;

            $dataLogger->log('landing-page-form-submitted');
            $this->sendmail($mailer, $email, $name, $optin);

            if ($data['optin'] ?? false) {
                $dataLogger->log('landing-page-newsletter-optin');
                $this->optin($email, $name, $optin);
            }

            return $this->render('main/landing_information_sent.html.twig', [
                'optin' => $optin,
            ]);
        }

        return $this->render('main/landing.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function sendmail(
        MailerInterface $mailer,
        string $email,
        string $name,
        bool $optin = false
    ): void {
        $message = (new TemplatedEmail())
            ->from(new Address('devrel@internal.platform.sh', 'Blackfire'))
            ->to(new Address(address: $email, name: $name))
            ->subject('Blackfire escape game')
            ->addPart((new DataPart(fopen($this->getParameter('kernel.project_dir') . '/assets/images/blackfire_logo.png', 'r'), 'logo', 'image/png'))->asInline())
            ->htmlTemplate('mail/landing.html.twig')
            ->textTemplate('mail/landing_text.html.twig')
            ->context([
                'name' => $name,
                'optin' => $optin,
            ])
        ;

        $mailer->send($message);
    }

    private function optin(string $email, string $name, bool $optin = false): void
    {
        if (!$optin) {
            return;
        }
    }
}
