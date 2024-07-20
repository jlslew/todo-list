<?php

namespace App\Fixture;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private array $users = [
        [
            'roles' => ['ROLE_SUPER_ADMIN', 'ROLE_ALLOWED_TO_SWITCH'],
            'email' => 'admin@gmail.com',
        ],
        [
            'email' => 'alice@gmail.com',
            'roles' => ['ROLE_USER'],
        ],
        [
            'email' => 'bob@gmail.com',
            'roles' => ['ROLE_USER'],
        ],
    ];

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
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
