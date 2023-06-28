<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index()
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user);
        
        return $this->render('user/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
    #[Route('/send', name: 'send_form', methods: 'POST')] 
    public function submitForm(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        // check if the form is valid
        if($form->isSubmitted() && $form->isValid())
        { 
            $name = $form->get('Name')->getData();
            $email = $form->get('Email')->getData();
            $message = $form->get('Message')->getData();

            // check if the form is empty
            if(!empty($name) &&!empty($email) && !empty($message)){
                $user->setName($name)
                ->setEmail($email)
                ->setMessage($message);

                // save data to database
                $entityManager->persist($user);
                $entityManager->flush();

                $responseMessage = "Köszönjük szépen a kérdésedet. Válaszunkkal hamarosan keresünk a megadott e-mail címen.";

                return $this->render('user/response.html.twig', [
                    'responseMessage' => $responseMessage
                ]); 
            }
            else {
                $responseMessage = 'Hiba! Kérjük töltsd ki az összes mezőt!';
                return $this->render('user/form.html.twig', [
                'responseMessage' => $responseMessage,
                'form' => $form->createView()
            ]); 
            }           
        }
        else {
            $responseMessage = 'Hiba! Kérjük töltsd ki az összes mezőt!';
            return $this->render('user/form.html.twig', [
                'responseMessage' => $responseMessage
            ]); 
        }
    }
}
