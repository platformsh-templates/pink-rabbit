<?php

namespace App\Controller;

use App\Entity\PinkRabbit;
use App\Entity\User;
use App\Form\AgreeToUpdatedTermsFormType;
use App\GitHub\GitHubApiHelper;
use App\Manager\SightingManager;
use App\Repository\PinkRabbitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function homepage(PinkRabbitRepository $pinkRabbitRepository): Response
    {
        $sightings = $this->createSightingsPaginator(1, $pinkRabbitRepository);

        $this->eatMe();

        return $this->render('main/homepage.html.twig', [
            'sightings' => $sightings,
            'nopouc' => $_ENV['EDOC_NOPOUC'] ?? '', // You really thought this would be in here, don't you?
        ]);
    }

    #[Route('/_sightings', name: 'app_sightings_partial_list')]
    public function loadSightingsPartial(PinkRabbitRepository $pinkRabbitRepository, Request $request): JsonResponse
    {
        // simple pagination!
        $page = $request->query->get('page', 1);
        $sightings = $this->createSightingsPaginator($page, $pinkRabbitRepository);

        $html = $this->renderView('main/_sightings.html.twig', [
            'sightings' => $sightings
        ]);

        $data = [
            'html' => $html,
            'next' => count($sightings) > 0 ? ++$page : null,
        ];

        return $this->json($data);
    }

    #[Route('/api/github-organization', name: 'app_github_organization_info')]
    public function gitHubOrganizationInfo(GitHubApiHelper $apiHelper): JsonResponse
    {
        $organizationName = 'platformsh-templates'; // 'SymfonyCasts';
        $organization = $apiHelper->getOrganizationInfo($organizationName);
        $repositories = $apiHelper->getOrganizationRepositories($organizationName);

        return $this->json([
            'organization' => $organization,
            'repositories' => $repositories,
        ]);
    }

    #[Route('/sighting/666', name: 'app_sighting_show_666')]
    public function showWhatsHidden(Request $request, SightingManager $sightingManager): Response
    {
        $sighting = $sightingManager->getSightingFromARequest($request);

        return $this->render('main/sighting_show.html.twig', [
            'sighting' => $sighting,
        ]);
    }

    #[Route('/sighting/{id}', name: 'app_sighting_show')]
    public function showSighting(PinkRabbit $pinkRabbit): Response
    {
        return $this->render('main/sighting_show.html.twig', [
            'sighting' => $pinkRabbit,
        ]);
    }

    #[Route('/terms/updated', name: 'agree_terms_update')]
    #[IsGranted('ROLE_USER')]
    public function agreeUpdatedTerms(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AgreeToUpdatedTermsFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $user->setAgreedToTermsAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirect(
                $request->headers->get('Referer') ?: $this->generateUrl('app_homepage')
            );
        }

        return $this->render('main/agreeUpdatedTerms.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('main/about.html.twig');
    }

    /**
     * @return Paginator|PinkRabbit[]
     */
    protected function createSightingsPaginator(int $page, PinkRabbitRepository $pinkRabbitRepository): Paginator
    {
        $maxResults = 25;
        $qb = $pinkRabbitRepository->findLatestQueryBuilder($maxResults);
        $qb->setFirstResult($maxResults * ($page - 1));

        return new Paginator($qb);
    }

    private function eatMe(): void
    {
        $start = time();
        $clockIsTickingButIDontWantToSleep = (2 * (2 ** 2 / 2 * (2 + 2)) ** 1 / 2 ** 2 - 2) / 2;
        while (time() - $start < $clockIsTickingButIDontWantToSleep) {
            /**
             * Every adventure requires a first step. Congratulations Alice!
             *
             * I am not crazy; my reality is just different from yours.
             *
             * Go and profile `/eat-me`, then compare profiles and realities.
             * https://blackfire.io/my/profiles
             *
             * Is the clock really ticking faster?
             * Can you see what's hidden and staring at you?
             */
        }
    }
}
