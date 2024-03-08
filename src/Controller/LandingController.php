<?php

namespace App\Controller;

use App\Form\LandingPageFormType;
use App\Service\EmailSender;
use Platformsh\DevRelBIPhpSdk\Symfony\DataLogger;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends MainController
{
    #[Route('/landing', name: 'landing_page')]
    public function landing(
        Request $request,
        FormFactoryInterface $formFactory,
        DataLogger $dataLogger,
        EmailSender $emailSender
    ): Response {
        $form = $formFactory->createNamed('', LandingPageFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = $data['email'] ?? null;
            $name = $data['name'] ?? null;
            $optin = $data['optin'] ?? false;

            $dataLogger->log('landing-page-form-submitted');
            $emailSender->sendmail($email, $name, $optin);

            if ($data['optin'] ?? false) {
                $dataLogger->log('landing-page-newsletter-optin');
                $emailSender->optin($email, $name, $optin);
            }

            return $this->render('main/landing_information_sent.html.twig', [
                'optin' => $optin,
            ]);
        }

        return $this->render('main/landing.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
