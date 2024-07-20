<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/task')]
class TaskController extends AbstractController
{
    #[Route('')]
    public function index(Security $security, TaskRepository $repository): Response
    {
        return $this->render('admin/task/index.html.twig', [
            'tasks' => $repository->getAllTasksAdminPage($security->getUser()),
        ]);
    }
}
