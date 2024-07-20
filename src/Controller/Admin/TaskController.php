<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route('/admin/task')]
class TaskController extends AbstractController
{
    public function __construct(private readonly RouterInterface $router)
    {
    }

    #[Route('/list', methods: ['GET'])]
    public function index(Security $security, TaskRepository $repository): Response
    {
        return $this->render('admin/task/index.html.twig', [
            'tasks' => $repository->getAllTasksAdminPage($security->getUser()),
        ]);
    }

    #[Route('/{id}', methods: ['GET', 'POST'])]
    public function update(?Task $task, Request $request, EntityManagerInterface $manager): Response {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('app_admin_task_index');
        }

        return $this->render('admin/task/form.html.twig', [
            'action' => $this->router->generate('app_admin_task_update', ['id' => $task->getId()]),
            'form' => $form->createView(),
            'task' => $task,
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
