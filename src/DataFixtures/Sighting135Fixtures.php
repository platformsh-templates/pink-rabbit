<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\PinkRabbitRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class Sighting135Fixtures extends Fixture implements FixtureGroupInterface
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var Generator */
    private $faker;

    public function __construct(
        private UserRepository $userRepository,
        private PinkRabbitRepository $pinkRabbitRepository
    ) {
    }

    public static function getGroups(): array
    {
        return ['secondary'];
    }

    public function load(ObjectManager $manager)
    {
        $this->objectManager = $manager;
        $this->faker = Factory::create();

        $this->createComments();

        $manager->flush();
    }

    private function createComments()
    {
        $sighting = $this->pinkRabbitRepository->find(135);
        $users = $this->userRepository->findAll();

        $this->createMany(500, function (int $i) use ($sighting, $users) {
            $comment = new Comment();
            if ($i % 5 === 0) {
                // make every 5th comment done by a small set of users
                // Wow! They must *love* Winky!
                $rangeMax = floor(count($users) / 10);
                $comment->setOwner($users[rand(0, $rangeMax)]);
            } else {
                $comment->setOwner($users[array_rand($users)]);
            }
            $comment->setPinkRabbit($sighting);
            $comment->setContent($this->faker->paragraph);
            $comment->setCreatedAt($this->faker->dateTimeBetween(
                $comment->getPinkRabbit()->getCreatedAt(),
                'now'
            ));

            return $comment;
        });
    }

    private function createMany(int $amount, callable $callback)
    {
        $objects = [];
        for ($i = 0; $i < $amount; $i++) {
            $object = $callback($i);
            $this->objectManager->persist($object);

            $objects[] = $object;

            if (($i % 100) === 0) {
                $this->objectManager->flush();
            }
        }

        $this->objectManager->flush();

        return $objects;
    }
}