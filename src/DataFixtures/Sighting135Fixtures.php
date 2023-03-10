<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\BigFootSightingRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class Sighting135Fixtures extends Fixture
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var Generator */
    private $faker;

    public function __construct(
        private UserRepository $userRepository,
        private BigFootSightingRepository $bigFootSightingRepository
    ) {
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
        $sighting = $this->bigFootSightingRepository->find(135);
        $users = $this->userRepository->findAll();

        $this->createMany(500, function (int $i) use ($sighting, $users) {
            $comment = new Comment();
            if ($i % 5 === 0) {
                // make every 5th comment done by a small set of users
                // Wow! They must *love* Bigfoot!
                $rangeMax = floor(count($users) / 10);
                $comment->setOwner($users[rand(0, $rangeMax)]);
            } else {
                $comment->setOwner($users[array_rand($users)]);
            }
            $comment->setBigFootSighting($sighting);
            $comment->setContent($this->faker->paragraph);
            $comment->setCreatedAt($this->faker->dateTimeBetween(
                $comment->getBigFootSighting()->getCreatedAt(),
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