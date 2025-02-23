<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProfileType;

final class ProfileController extends AbstractController
{
    
    #[Route('/profile', name: 'app_profile')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser(); 
        
        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

