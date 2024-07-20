<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getAllTasksHomePage()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getAllTasksAdminPage(?UserInterface $user)
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.user', 'u')
            ->where('u.email = :user')
            ->setParameter('user', $user->getUserIdentifier())
            ->getQuery()
            ->getResult();
    }
}
