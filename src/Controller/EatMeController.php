<?php

namespace App\Controller;

use App\Entity\CheshireCat;
use App\Repository\PinkRabbitRepository;
use Platformsh\DevRelBIPhpSdk\Symfony\Track;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EatMeController extends MainController
{
    #[Route('/eat-me', name: 'app_eat_me_homepage')]
    public function homepage(PinkRabbitRepository $pinkRabbitRepository): Response
    {
        $this->floatingSmile();

        $sightings = $this->createSightingsPaginator(1, $pinkRabbitRepository);

        return $this->render('main/homepage.html.twig', [
            'sightings' => $sightings
        ]);
    }

    private function floatingSmile(): void
    {
        $chessy = new CheshireCat();
        $chessy->followMe();
    }
}
