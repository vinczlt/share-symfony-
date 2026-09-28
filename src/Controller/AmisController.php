<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\AjoutAmiType;
use App\Repository\FichierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AmisController extends AbstractController
{
    #[Route('/private-amis', name: 'app_amis')]
    public function amis(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response {
        if ($request->get('id') != null) {
            $id = $request->get('id');
            $userDemande = $userRepository->find($id);
            if ($userDemande) {
                $this->getUser()->removeDemander($userDemande);
                $em->persist($this->getUser());
                $em->flush();
            }
        }
        if ($request->get('idRefuser') != null) {
            $id = $request->get('idRefuser');
            $userRefuser = $userRepository->find($id);
            if ($userRefuser) {
                $this->getUser()->removeUsersDemande($userRefuser);
                $em->persist($this->getUser());
                $em->flush();
            }
        }

        if ($request->get('idAccepter') != null) {
            $id = $request->get('idAccepter');
            $userAccepter = $userRepository->find($id);
            if ($userAccepter) {
                $this->getUser()->addAccepter($userAccepter);
                $userAccepter->addAccepter($this->getUser());
                $this->getUser()->removeUsersDemande($userAccepter);
                $em->persist($this->getUser());
                $em->persist($userAccepter);
                $em->flush();
            }
        }

        $form = $this->createForm(AjoutAmiType::class);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $ami = $userRepository->findOneBy(array('email' => $form->get('email')->getData()));
                if (!$ami) {
                    $this->addFlash('notice', 'Ami introuvable');
                    return $this->redirectToRoute('app_amis');
                } else {
                    $this->getUser()->addDemander($ami);
                    $em->persist($this->getUser());
                    $em->flush();
                    $this->addFlash('notice', 'Invitation envoyée');
                    return $this->redirectToRoute('app_amis');
                }
            }
        }
        return $this->render('amis/amis.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/private-supprimer-amis/{id}', name: 'app_supprimer_amis')]
    public function supprimerAmis(Request $request, User $ami, EntityManagerInterface $em): Response
    {
        if ($ami != null) {
            $this->getUser()->removeAccepter($ami);
            $em->persist($this->getUser());
            $em->flush();
            $this->addFlash('notice', 'Ami supprimée');
        }
        return $this->redirectToRoute('app_amis');
    }
    #[Route('/private-listefichierami', name: 'app_liste_social')]
    public function listefichier(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy([], ['nom' => 'asc', 'prenom' => 'asc']);
        return $this->render('amis/liste-fichier-ami.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/fichier/envoyer/{fichier_id}/{ami_id}', name: 'app_envoyer_fichier')]
    public function envoyerFichier(int $fichier_id, int $ami_id, FichierRepository $fichierRepo, UserRepository $userRepo, EntityManagerInterface $em): Response
    {
        $fichier = $fichierRepo->find($fichier_id);
        $ami = $userRepo->find($ami_id);

        if (!$fichier || !$ami) {
            throw $this->createNotFoundException('Fichier ou Ami introuvable.');
        }
        $ami->addFichiersRecu($fichier);

        $em->flush();

        $this->addFlash('success', 'Le fichier a bien été envoyé à ' . $ami->getPrenom());

        return $this->redirectToRoute('app_liste_social');
    }
    #[Route('/mes-fichiers-recus', name: 'app_fichiers_recus')]
    public function fichiersRecus(): Response
    {
        $users = $this->getUser();

        if (!$users) {
            return $this->redirectToRoute('app_login');
        }

        $fichiersRecus = $users->getFichiersRecus();

        return $this->render('amis/fichiers_reçu.html.twig', [
            'fichiers_recus' => $fichiersRecus,
            'user' => $users,
        ]);
    }
    #[Route('/fichier/annuler-partage/{fichier_id}/{ami_id}', name: 'app_annuler_partage')]
    public function annulerPartage(int $fichier_id, int $ami_id, FichierRepository $fichierRepo, UserRepository $userRepo, EntityManagerInterface $em): Response
    {
        $fichier = $fichierRepo->find($fichier_id);
        $ami = $userRepo->find($ami_id);

        if (!$fichier || !$ami) {
            throw $this->createNotFoundException('Fichier ou Ami introuvable.');
        }

        $userConnecte = $this->getUser();

        if ($fichier->getUser() !== $userConnecte) {
            throw new AccessDeniedException('Action non autorisée. Ce fichier ne vous appartient pas.');
        }

        $ami->removeFichiersRecu($fichier);

        $em->flush();

        $this->addFlash('success', 'Le partage du fichier avec ' . $ami->getPrenom() . ' a bien été annulé.');

        return $this->redirectToRoute('app_liste_social');
    }
}
