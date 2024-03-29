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
        for ($i = 0; $i < 42; $i++) {
            $this->httpClient->request(
                'GET',
                $request->getSchemeAndHttpHost() . '/pink_winky_blinking.gif'
            );
        }

        /**
         * Al!ce, only a few find the way, some don't recognize it when they do;
         * some… don't ever want to.
         *
         * Look for `pink_winky_blinking` in the call-graph and click on the
         * magnifying glass. It's showing you the sequences of calls leading
         * to that HTTP call.
         *
         * Similarly, you could also search for a SQL query. It's like a
         * Marauder's Map for you app.
         *
         * The Queen of Heart wants to make your app headless. The HTTP tab is
         * listing all the calls to your webservices. The SQL one all the
         * requests to your database.
         *
         * It would be crazy not to use it. Have you give it a try?
         * Good job, but we're not done.
         *
         * Now, let's get in the Queen's castle curtain walls to play cricket.
         * Profile the `/login` page and check its timeline.
         */

        return $this->pinkRabbitRepository->findOneByTitle('Follow me!');
    }
}
