<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Repository\CommissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommissionController extends AbstractController
{
    #[Route('/commission/create', name: 'app_create_commission', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données du formulaire
        $name = $request->request->get('name');
        $temporaryDate = $request->request->get('temporaryDate');
    
        // Créer une nouvelle commission
        $commission = new Commission();
        $commission->setName($name);
        $commission->setCreatedAt(new \DateTime());
        $commission->setClosed(false);
        $commission->setAuthor($this->getUser());
    
        // Gestion de la date temporaire (optionnelle)
        if ($temporaryDate) {
            $temporaryDateObj = \DateTime::createFromFormat('Y-m-d\TH:i', $temporaryDate);
            $commission->setTemp(true, $temporaryDateObj);
        } else {
            $commission->setTemp(false);
        }
    
        // Sauvegarder dans la base de données
        $entityManager->persist($commission);
        $entityManager->flush();
    
        // Rediriger ou retourner une réponse
        return $this->redirectToRoute('app_chat');
    }
    
}
