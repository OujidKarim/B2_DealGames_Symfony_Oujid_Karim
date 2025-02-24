<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Form\DeleteAnnonceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AnnonceController extends AbstractController
{
    #[Route('/annonce', name: 'app_annonce')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }
        
        $repository = $entityManager->getRepository(Annonce::class);
        $annonces = $repository->findAll();


        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }



    #[Route('/annonce/add', name: 'new_annonce')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            
            $annonce -> setTitle($form->get('title')->getData());
            $annonce -> setDescription($form->get('description')->getData());
            $annonce -> setImageFile($form->get('imageFile')->getData());
            $annonce -> setAuthor($user);
            $annonce -> setCategories($form->get('categories')->getData());
            $annonce -> setPrix($form->get('prix')->getData());
            $annonce -> setVille($form->get('ville')->getData());
            $annonce -> setCodePostal($form->get('codePostal')->getData());
            $annonce -> setDateCreation(new \DateTimeImmutable());
            $annonce -> setDateModification(new \DateTimeImmutable());
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_annonce');
        }


        return $this->render('annonce/add.html.twig', [
            'app_annonce' => $form,
        ]);


    }


    #[Route('/annonce/{id}', name: 'details_annonce')]
    public function seeOne(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $annonce = $entityManager->getRepository(Annonce::class)->find($id);  

        return $this->render('annonce/annonceDetails.html.twig', [
            'annonce' => $annonce
        ]);
    }

    #[Route('/annonceUpdate/{id}', name: 'update_annonce')]
    public function update(EntityManagerInterface $em, Request $request, int $id): Response
    {
        /** @var \App\Entity\User $user */ 
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $annonce = $em->getRepository(Annonce::class)->find($id);
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        $author = $user->getUsername(); 
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setTitle($form->get('title')->getData());
            $annonce->setDescription($form->get('description')->getData());
            $annonce->setAuthor($user);
            $image = $form->get('imageFile')->getData();
            if($image){
                $annonce->setImageFile($image);
                
            }
            $annonce->setCategories($form->get('categories')->getData());
            $em->flush();   

            return $this->redirectToRoute('app_annonce');
        }

        return $this->render('annonce/add.html.twig', [
            'app_annonce' => $form,
            'formTitle' => 'Mettre à jour l\'annonce.',
            'author' => $author,
            'annonce' => $annonce,
            'buttonText' => 'Mettre à jour'
        ]);
    }


    #[Route('/annonce/delete/{id}', name: 'delete_annonce')]
    public function deleteOne(EntityManagerInterface $em, int $id, Request $request): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $annonce = $em->getRepository(Annonce::class)->find($id);

        if (!$annonce) {
            throw $this->createNotFoundException('L\'annonce n\'existe pas');
        }

        $em->remove($annonce);
        $em->flush();

        $this->addFlash('notice', 'Votre annonce à bien était supprimée !');

        return $this->redirectToRoute('app_annonce');
    }
    





}
