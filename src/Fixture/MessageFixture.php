<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Message;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MessageFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var Task[] $tasks */
        $tasks = $this->referenceRepository->getReferencesByClass()[Task::class];
        $faker = Factory::create();

        for ($i = 0; $i < 100; ++$i) {
            $message = new Message();
            $message->setContent($faker->realText(255, 5));

            /** @var Task $task */
            $task = $faker->randomElement($tasks);
            $message->setTask($this->getReference($task->getTitle()));

            $this->addReference($message->getContent(), $message);
            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TaskFixture::class,
        ];
    }
}
