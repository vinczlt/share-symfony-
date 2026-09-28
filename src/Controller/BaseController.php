<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Contact;
use App\Entity\Fichier;
use App\Form\CategorieType;
use App\Form\ChangePasswordType;
use App\Form\ContactType;
use App\Form\FichierUserType;
use App\Repository\ScategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BaseController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
        ]);
    }
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $contact->setDateEnvoi(new \Datetime());
                $em->persist($contact);
                $em->flush();
                $this->addFlash('notice', 'Message envoyé');
                return $this->redirectToRoute('app_contact');
            }
        }
        return $this->render('base/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/apropos', name: 'app_apropos')]
    public function apropos(): Response
    {
        return $this->render('base/apropos.html.twig', [
        ]);
    }
    #[Route('/mentionlegales', name: 'app_mentionlegales')]
    public function mentionlegales(): Response
    {
        return $this->render('base/mentionlegales.html.twig', [
        ]);
    }
    #[Route('/private-categorie', name: 'app_ajout_categorie')]
    public function importation(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $categorie->setDateEnvoi(new \Datetime());
                $em->persist($categorie);
                $em->flush();
                $this->addFlash('notice', 'Categorie envoyé');
                return $this->redirectToRoute('app_ajout_categorie');
            }
        }
        return $this->render('base/categorie.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/private-profil', name: 'app_profil')]
    public function profil(Request $request, ScategorieRepository $scategorieRepository, EntityManagerInterface $em, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher, ): Response
    {
        $fichier = new Fichier();
        $scategories = $scategorieRepository->findBy([], ['categorie' => 'asc', 'numero' => 'asc']);
        $form = $this->createForm(FichierUserType::class, $fichier, ['scategories' => $scategories]);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $selectedScategories = $form->get('scategories')->getData();
                foreach ($selectedScategories as $scategorie) {
                    $fichier->addScategory($scategorie);
                }
                $file = $form->get('fichier')->getData();
                if ($file) {
                    $nomFichierServeur = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $nomFichierServeur = $slugger->slug($nomFichierServeur);
                    $nomFichierServeur = $nomFichierServeur . '-' . uniqid() . '.' . $file->guessExtension();
                    try {
                        $fichier->setNomServeur($nomFichierServeur);
                        $fichier->setNomOriginal($file->getClientOriginalName());
                        $fichier->setDateEnvoi(new \Datetime());
                        $fichier->setExtension($file->guessExtension());
                        $fichier->setTaille($file->getSize());
                        $fichier->setUser($this->getuser());
                        $em->persist($fichier);
                        $em->flush();
                        $file->move($this->getParameter('file_directory'), $nomFichierServeur);
                        $this->addFlash('notice', 'Fichier envoyé');
                        return $this->redirectToRoute('app_profil');
                    } catch (FileException $e) {
                        $this->addFlash('notice', 'Erreur d\'envoi');
                    }
                }
            }
        }
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $changePasswordForm= $this->createForm(ChangePasswordType::class);
        $changePasswordForm->handleRequest($request);

        if ($changePasswordForm->isSubmitted()) {

            $oldPassword = $changePasswordForm->get('oldPassword')->getData();
            $newPassword = $changePasswordForm->get('newPassword')->getData();

            if ($oldPassword === $newPassword && $newPassword !== null) {
                $changePasswordForm->get('newPassword')->addError(
                    new FormError('Ton nouveau mot de passe doit être différent de l\'actuel.')
                );
            }

            if ($changePasswordForm->isValid()) {

                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $newPassword
                );

                $user->setPassword($hashedPassword);
                $entityManager->flush();

                $this->addFlash('success', 'Ton mot de passe a bien été mis à jour !');
            }
        }
        return $this->render('base/profil.html.twig', [
            'changePasswordForm' => $changePasswordForm->createView(),
            'form' => $form->createView(),
            'scategories' => $scategories,
        ]);
    }
}
