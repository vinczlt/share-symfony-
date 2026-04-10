<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ContactRepository;

class ContactController extends AbstractController
{
    #[Route('/mod-liste-contacts', name: 'liste-contacts')]
    public function listeContacts(ContactRepository $contactRepository): Response
    {
        $contacts=$contactRepository->findAll();
        return $this->render('contact/index.html.twig', [
            'contacts'=>$contacts
        ]);
    }
}
