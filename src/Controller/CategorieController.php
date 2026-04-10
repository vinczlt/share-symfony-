<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\ModifierCategorieType;
use App\Form\SupprimerCategorieType;
use App\Form\ScategorieType;
use App\Repository\ScategorieRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategorieController extends AbstractController
{
    #[Route('/private-liste-categorie', name: 'app_liste_categorie', methods: ['GET', 'POST'])]
    public function listeCategorie(Request $request, CategorieRepository $categorieRepository, EntityManagerInterface $em): Response
    {
        $categories = $categorieRepository->findAll();
        $form = $this->createForm(SupprimerCategorieType::class, null, [
            'categories' => $categories,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (in_array ('ROLE_ADMIN',$this->getUser()->getRoles())){

            
            $selectedCategories = $form->get('categories')->getData();
            foreach ($selectedCategories as $categorie) {
                $em->remove($categorie);
            }
            $em->flush();
            $this->addFlash('notice', 'Catégories supprimées avec succès');
            return $this->redirectToRoute('app_liste_categorie');
        } else {
            $this->addFlash('notice', 'Vous n\'avez pas les droits');
            return $this->redirectToRoute('app_liste_categorie');
        }
        }
        return $this->render('categorie/liste-categorie.html.twig', [
            'categories' => $categories,
            'form'=>$form->createView(),
        ]);
    }
    #[Route('/admin-modifier-categorie/{id}', name: 'app_modifier_categorie')]
    public function modifCategorie(Request $request, Categorie $categorie, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifierCategorieType::class, $categorie);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($categorie);
                $em->flush();
                $this->addFlash('notice', 'Catégorie modifiée');
                return $this->redirectToRoute('app_liste_categorie');
            }
        }
        return $this->render('categorie/modifier-categorie.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/admin-supprimer-categorie/{id}', name: 'app_supprimer_categorie')]
    public function supprimerCategorie(Request $request, Categorie $categorie, EntityManagerInterface $em): Response {
        if ($categorie != null) {
            $em->remove($categorie);
            $em->flush();
            $this->addFlash('notice', 'Catégorie supprimée');
        }
        return $this->redirectToRoute('app_liste_categorie');
    }
}
