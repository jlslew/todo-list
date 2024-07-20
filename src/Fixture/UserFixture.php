<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private array $users = [
        [
            'roles' => ['ROLE_ADMIN', 'ROLE_ALLOWED_TO_SWITCH'],
            'email' => 'admin@gmail.com',
        ],
        [
            'email' => 'user@gmail.com',
            'roles' => ['ROLE_USER'],
        ],
    ];

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $emails = [];

        try {
            for ($i = 0; $i < 10; ++$i) {
                $emails[] = $faker->unique()->email;
            }
        } finally {
            foreach ($emails as $email) {
                $this->users[] = [
                    'roles' => ['ROLE_USER'],
                    'email' => $email,
                ];
            }
        }

        foreach ($this->users as $u) {
            $user = new User();
            $user->setEmail($u['email']);

            $this->addReference($u['email'], $user);
            $manager->persist($user);

            $user->setPassword($this->hasher->hashPassword($user, '12345'));
            $user->setRoles($u['roles']);
        }

        $manager->flush();
    }
}
