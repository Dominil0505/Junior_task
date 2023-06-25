<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index()
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user);

        return $this->render('user/form.html.twig', [
            'form' => $form
        ]);
    }
}
