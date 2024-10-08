<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Commission;
use App\Repository\MessageRepository;
use App\Repository\CommissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    #[Route('/chat/{commissionId?1}', name: 'app_chat')]
    public function index(
        CommissionRepository $commissionRepository, 
        MessageRepository $messageRepository, 
        int $commissionId = 1
        ): Response
    {
        // Récupération de la commission sélectionnée (par défaut, celle avec id = 1)
        $commission = $commissionRepository->find($commissionId);

        // Si la commission n'existe pas, on affiche un message d'erreur
        if (!$commission) {
            $messages = [];
            $commissionName = "Aucune commission trouvée";
        } else {
            // Récupération des messages liés à la commission
            $messages = $messageRepository->findBy(['commission' => $commission]);
            $commissionName = $commission->getName();
        }

        // Récupération de toutes les commissions pour le menu latéral
        $commissions = $commissionRepository->findAll();

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'commissions' => $commissions,
            'messages' => $messages,
            'currentCommission' => $commissionName,
            'current_commission_id' => $commission ? $commission->getId() : null
        ]);
    }

    #[Route('/chat/send/{commissionId}', name: 'app_chat_send', methods: ['POST'])]
    public function sendMessage(
        Request $request, 
        CommissionRepository $commissionRepository,  
        EntityManagerInterface $entityManager,
        int $commissionId
        ): Response
    {
        // Récupération de la commission sélectionnée (par défaut, celle avec id = 1)
        $commission = $commissionRepository->find($commissionId);

        $messageText = $request->request->get('message');
        

        if (!empty($messageText)) {
            // Vérifie si le champ 'name' de la commission est bien défini
            if (!$commission->getName()) {
                throw new \Exception('La commission doit avoir un nom.');
            }
    
            $message = new Message();
            $message->setText($messageText);
            $message->setCreatedAt(new \DateTime());
            $message->setUser($this->getUser());
            $message->setCommission($commission);
            
            $entityManager->persist($message);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_chat', ['commissionId' => $commission->getId()]);
    }
}
