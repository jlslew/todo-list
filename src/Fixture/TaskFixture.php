<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Task;
use App\Entity\User;
use App\Faker\Provider\DecoratedImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TaskFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var User[] $users */
        $users = $this->referenceRepository->getReferencesByClass()[User::class];
        $faker = Factory::create();

        $faker->addProvider(new DecoratedImage($faker));
        $titles = [];

        try {
            for ($i = 0; $i < 50; ++$i) {
                $titles[] = $faker->unique()->realText(25);
            }
        } finally {
            foreach ($titles as $title) {
                $task = new Task();
                $task->setTitle($title);

                /** @var User $user */
                $user = $faker->randomElement($users);
                $task->setUser($this->getReference($user->getEmail()));

                $task->setDescription($faker->optional()->realText(255, 5));
                $path = $faker->optional()->image();

                if ($path) {
                    $task->setImageFile(new UploadedFile($path, basename($path), test: true));
                }

                $manager->persist($task);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}
