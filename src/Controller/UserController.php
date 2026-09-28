<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\Fichier;
use App\Repository\FichierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/admin-listuser', name: 'liste-user')]
    public function listuser(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/private-telechargement-fichier-user/{id}', name: 'app_telechargement_fichier_user',
        requirements: ["id" => "\d+"])]
    public function telechargementFichierUser(Fichier $fichier)
    {
        if ($fichier == null) {
            return $this->redirectToRoute('app_profil');
        } else {
            if ($fichier->getUser()!== $this->getUser()) {
                $this->addFlash('notice', 'Vous n\'êtes pas le propriétaire de ce fichier');
                return $this->redirectToRoute('app_profil');
            }
            return $this->file($this->getParameter('file_directory') . '/' . $fichier->getNomServeur(),
                $fichier->getNomOriginal());
        }
    }
    #[Route('/private-telechargement-fichier-ami/{id}', name: 'app_telechargement_fichier_ami',
        requirements: ["id" => "\d+"])]
    public function telechargementFichierAmi(Fichier $fichier)
    {
        if ($fichier == null) {
            return $this->redirectToRoute('app_profil');
        }
            return $this->file($this->getParameter('file_directory') . '/' . $fichier->getNomServeur(),
                $fichier->getNomOriginal());
    }
}
