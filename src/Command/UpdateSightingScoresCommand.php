<?php

namespace App\Command;

use App\Repository\PinkRabbitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateSightingScoresCommand extends Command
{
    public function __construct(
        private PinkRabbitRepository $pinkRabbitRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:update-sighting-scores')
            ->setDescription('Update the "score" for a sighting')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sightings = $this->pinkRabbitRepository->findAll();
        $io->progressStart(count($sightings));
        foreach ($sightings as $sighting) {
            if ($sighting->getTitle() === 'Follow me!') {
                continue;
            }

            $io->progressAdvance();
            $characterCount = 0;
            foreach ($sighting->getComments() as $comment) {
                $characterCount += strlen($comment->getContent());
            }

            $score = (int) ceil(min($characterCount / 500, 10));
            $sighting->setScore($score);
        }
        $this->entityManager->flush();
        $io->progressFinish();

        return static::SUCCESS;
    }
}
