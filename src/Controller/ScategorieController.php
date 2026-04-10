<?php
namespace App\Controller;

use App\Form\ModifierScategorieType;
use App\Entity\Scategorie;
use App\Form\ScategorieType;
use App\Form\SupprimerScategorieFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ScategorieController extends AbstractController
{
    #[Route('/private-ajout-scategorie', name: 'app_ajout-scategorie')]
    public function ajoutScategorie(Request $request, EntityManagerInterface $em): Response
    {
        $scategorie = new Scategorie();
        $form = $this->createForm(ScategorieType::class, $scategorie);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $em->persist($scategorie);
                    $em->flush();
                } catch (\RuntimeException $e) {
                    $this->addFlash('notice', $e->getMessage());
                    return $this->redirectToRoute('app_ajout-scategorie');
                }
                $this->addFlash('notice', 'Sous catégorie insérée');
                return $this->redirectToRoute('app_ajout-scategorie');
            }
        }
        return $this->render('scategorie/ajout-scategorie.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/admin-modifier-scategorie/{id}', name: 'app_modifier_scategorie')]
    public function modifierScategorie(Request $request, Scategorie $scategorie, EntityManagerInterface $em): Response
    {
        $form=$this->createForm(ModifierScategorieType::class, $scategorie);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
            $em->persist($scategorie);
            $em->flush();
            $this->addFlash('notice','Sous catégorie modifiée');
            return $this->redirectToRoute('app_liste_categories');
            }
            }
        return $this->render('scategorie/modifier-scategorie.html.twig', [
            'form'=>$form->createView()

        ]);
    }
    #[Route('/admin-supprimer-scategorie/{id}', name: 'app_supprimer_scategorie')]
    public function supprimerScategorie(Request $request, Scategorie $scategorie, EntityManagerInterface $em): Response {
        if ($scategorie != null) {
            $em->remove($scategorie);
            $em->flush();
            $this->addFlash('notice', 'Sous catégorie supprimée');
        }
        return $this->redirectToRoute('app_liste_categorie');
    }
}
