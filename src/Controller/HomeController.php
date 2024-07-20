<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(TaskRepository $repository): Response
    {
        return $this->render('home/index.html.twig', [
            'tasks' => $repository->getAllTasksHomePage(),
        ]);
    }
}
