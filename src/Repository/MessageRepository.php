<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getAllMessages(?UserInterface $user): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.task', 't')
            ->leftJoin('t.user', 'u')
            ->where('u.email = :user')
            ->setParameter('user', $user->getUserIdentifier())
            ->getQuery()
            ->getArrayResult();
    }
}
