<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/sign-up')]
    public function signup(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();

        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $manager->persist($user);
                $manager->flush();
            } catch (UniqueConstraintViolationException $exception) {
                $form->addError(new FormError('Account already exists!'));

                return $this->render('user/sign-up.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('user/sign-up.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
