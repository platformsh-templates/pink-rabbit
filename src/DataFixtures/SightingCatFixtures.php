<?php

namespace App\DataFixtures;

use App\Entity\PinkRabbit;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\PinkRabbitRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SightingCatFixtures extends Fixture implements FixtureGroupInterface
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var Generator */
    private $faker;

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private UserRepository $userRepository,
        private PinkRabbitRepository $pinkRabbitRepository
    ) {
    }

    public static function getGroups(): array
    {
        return ['secondary'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->objectManager = $manager;
        $this->faker = Factory::create();

        $this->createSighting666IfNeeded();

        $manager->flush();
    }

    private function createSighting666IfNeeded(): void
    {
        $sighting = $this->pinkRabbitRepository->find(666);
        if ($sighting) {
            return;
        }

        $users = $this->userRepository->findAll();

        $cheshireCat = $this->createCheshireCat();

        $sighting = new PinkRabbit();
        $sighting->setOwner($cheshireCat);
        $sighting->setTitle('Follow me!');
        $sighting->setDescription('Look at this page: is someone giving you an advice? Then, profile the page and look for the hidden image URL HTTP call.');
        $sighting->setConfidenceIndex(10);
        $sighting->setScore(666);
        $sighting->setLatitude($this->faker->latitude);
        $sighting->setLongitude($this->faker->longitude);
        $sighting->setCreatedAt($this->faker->dateTimeBetween('-6 months', 'now'));

        $this->objectManager->persist($sighting);
        $this->objectManager->flush();

        $this->createComments($sighting, $users);

        $comment = new Comment();
        $comment->setOwner($cheshireCat);
        $comment->setPinkRabbit($sighting);
        $comment->setContent('<-------- Hello! Is it me you\'re looking for? There is an HTTP tab on the profile. Which function is making that call?');
        $comment->setCreatedAt($this->faker->dateTimeBetween(
            $sighting->getCreatedAt(),
            'now'
        ));
        $this->objectManager->persist($comment);

        $this->createComments($sighting, $users);
    }

    private function createCheshireCat(): User
    {
        $user = new User();
        $user->setUsername('Chessy');
        $user->setEmail('chessy@not-realy-here.com');
        $user->setPassword(
            $this->passwordEncoder->hashPassword($user, 'believe')
        );
        $user->setAgreedToTermsAt($this->faker->dateTimeBetween('-6 months', 'now'));

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        return $user;
    }

    /**
     * @param array<int, User> $users
     */
    private function createComments(PinkRabbit $sighting, array $users): void
    {
        $this->createMany(5, function(int $i) use ($sighting, $users) {
            $comment = new Comment();
            if ($i % 5 === 0) {
                // make every 5th comment done by a small set of users
                // Wow! They must *love* Winky!
                $rangeMax = (int) floor(count($users) / 10);
                $comment->setOwner($users[rand(0, $rangeMax)]);
            } else {
                $comment->setOwner($users[array_rand($users)]);
            }
            $comment->setPinkRabbit($sighting);
            $comment->setContent($this->faker->paragraph);
            $comment->setCreatedAt($this->faker->dateTimeBetween(
                $sighting->getCreatedAt(),
                'now'
            ));

            return $comment;
        });
    }

    /**
     * @return array<int, Comment>
     */
    private function createMany(int $amount, callable $callback)
    {
        $objects = [];
        for ($i = 0; $i < $amount; $i++) {
            $object = $callback($i);
            $this->objectManager->persist($object);

            $objects[] = $object;
        }
        $this->objectManager->flush();

        return $objects;
    }
}
