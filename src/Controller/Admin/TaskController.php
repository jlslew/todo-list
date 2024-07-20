<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/task')]
class TaskController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(Security $security, TaskRepository $repository): Response
    {
        return $this->render('admin/task/index.html.twig', [
            'tasks' => $repository->getAllTasksAdminPage($security->getUser()),
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Task $task, Request $request, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete-' . $task->getId(), $request->request->get('_token'))) {
            $manager->remove($task);
            $manager->flush();
        }

        return $this->redirectToRoute('app_admin_task_index');
    }
}
