<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Task;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
    #[Route('/messages', methods: ['GET'])]
    public function getMessages(Security $security, MessageRepository $repository): JsonResponse
    {
        return new JsonResponse($repository->getAllMessages($security->getUser()));
    }

    #[Route('/message/{task}', methods: ['POST'])]
    public function postMessage(Task $task, Request $request, EntityManagerInterface $manager): JsonResponse
    {
        if (trim($request->getContent())) {
            $message = new Message();
            $message->setTask($task);
            $message->setContent($request->getContent());

            $manager->persist($message);
            $manager->flush();
        }

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
