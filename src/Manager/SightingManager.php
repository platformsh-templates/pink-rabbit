<?php

namespace App\Manager;

use App\Entity\PinkRabbit;
use App\Repository\PinkRabbitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SightingManager
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private PinkRabbitRepository $pinkRabbitRepository
    ) {
    }

    public function getSightingFromARequest(Request $request): PinkRabbit
    {
        die($request->getSchemeAndHttpHost() . '/pink_winky_blinking.gif');
    
        $this->httpClient->request(
            'GET',
            $request->getSchemeAndHttpHost() . '/pink_winky_blinking.gif'
        );

        /**
         * Al!ce, only a few find the way, some don’t recognize it when they do;
         * some… don’t ever want to.
         * 
         * Look for `pink_winky_blinking` in the call-graph and click on the
         * magnifying glass. It's showing you the sequences of calls leading
         * to that HTTP call.
         * 
         * The Queen of Heart wants to make your app headless. The HTTP tab is
         * listing all the calls to your webservices.
         * 
         * It would be crazy not to use it.
         * 
         * Let's get in the Queen's castle curtain walls to play cricket.
         * Profile the `/login` page
         */

        return $this->pinkRabbitRepository->findOneByTitle('Follow me!');
    }
}
