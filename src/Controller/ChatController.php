<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageReadStatus;
use App\Repository\MessageRepository;
use App\Repository\MessageReadStatusRepository;
use App\Repository\CommissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    #[Route('/chat/{commissionId?1}', name: 'app_chat')]
    public function index(
        CommissionRepository $commissionRepository,
        MessageRepository $messageRepository,
        MessageReadStatusRepository $readStatusRepository,
        int $commissionId = 1
    ): Response {
        $user = $this->getUser();
        $commission = $commissionRepository->find($commissionId);
    
        if (!$commission) {
            $messages = [];
            $commissionName = "Aucune commission trouvée";
            $isClosed = true;
        } else {
            $messages = $messageRepository->findBy(['commission' => $commission]);
            $commissionName = $commission->getName();
            $isClosed = $commission->getClosedAt() && $commission->getClosedAt() < new \DateTime();
        }
    
        $commissions = $commissionRepository->findAll();
    
        $notifications = [];
        foreach ($commissions as $comm) {
            $notifications[$comm->getId()] = $readStatusRepository->countUnreadMessages($comm, $user);
        }
    
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'commissions' => $commissions,
            'messages' => $messages,
            'currentCommission' => $commissionName,
            'current_commission_id' => $commission ? $commission->getId() : null,
            'isClosed' => $isClosed,
            'notifications' => $notifications,
        ]);
    }

    #[Route('/message/{id}/read', name: 'mark_message_read', methods: ['POST'])]
    public function markMessageAsRead(
        int $id,
        EntityManagerInterface $entityManager,
        MessageRepository $messageRepository,
        MessageReadStatusRepository $readStatusRepository,
        ValidatorInterface $validator
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $message = $messageRepository->find($id);
        if (!$message) {
            return $this->json(['error' => 'Message not found'], Response::HTTP_NOT_FOUND);
        }

        $readStatus = $readStatusRepository->findOneBy([
            'message' => $message,
            'user' => $user,
        ]);

        if (!$readStatus) {
            $readStatus = new MessageReadStatus();
            $readStatus->setMessage($message);
            $readStatus->setUser($user);
            $readStatus->setCommission($message->getCommission());
        }

        $readStatus->setIsRead(true);

        // Validation de l'entité
        $errors = $validator->validate($readStatus);
        if (count($errors) > 0) {
            return $this->json(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($readStatus);
        $entityManager->flush();

        return $this->json(['success' => true], Response::HTTP_OK);
    }

    #[Route('/chat/send/{commissionId}', name: 'app_chat_send', methods: ['POST'])]
    public function sendMessage(
        Request $request, 
        CommissionRepository $commissionRepository,  
        EntityManagerInterface $entityManager,
        HubInterface $hub,  // Ajout du Hub Mercure
        int $commissionId
    ): Response {
        $commission = $commissionRepository->find($commissionId);
    
        if (!$commission) {
            throw $this->createNotFoundException('La commission n\'existe pas.');
        }
    
        // Vérifiez si la commission est fermée
        if ($commission->getClosedAt() && $commission->getClosedAt() < new \DateTime()) {
            $this->addFlash('error', 'Cette commission est fermée. Vous ne pouvez pas envoyer de messages.');
            return $this->redirectToRoute('app_chat', ['commissionId' => $commissionId]);
        }
    
        $messageText = $request->request->get('message');
    
        if (!empty($messageText)) {
            $message = new Message();
            $message->setText($messageText);
            $message->setCreatedAt(new \DateTime());
            $message->setUser($this->getUser());
            $message->setCommission($commission);
            
            $entityManager->persist($message);
            $entityManager->flush();
    
            // Publier sur Mercure
            $update = new Update(
                'https://localhost/chat/' . $commissionId,  // Le topic à écouter
                json_encode([
                    'user' => [
                        'id' => $this->getUser()->getId(),
                        'name' => $this->getUser()->getNom(),
                    ],
                    'message' => $message->getText(),
                    'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
                ])
            );
            $hub->publish($update);  // Envoi de la mise à jour au Hub Mercure
        }
    
        return $this->redirectToRoute('app_chat', ['commissionId' => $commission->getId()]);
    }    

    #[Route('/messages/mark-as-read', name: 'mark_messages_read', methods: ['POST'])]
    public function markMessagesAsRead(
        Request $request,
        EntityManagerInterface $entityManager,
        MessageRepository $messageRepository,
        MessageReadStatusRepository $readStatusRepository
    ): JsonResponse {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
    
        if (!isset($data['messageIds']) || !is_array($data['messageIds'])) {
            return $this->json(['error' => 'Invalid message IDs'], Response::HTTP_BAD_REQUEST);
        }
    
        $messages = $messageRepository->findBy(['id' => $data['messageIds']]);
    
        foreach ($messages as $message) {
            $readStatus = $readStatusRepository->findOneBy([
                'message' => $message,
                'user' => $user,
            ]);
    
            if (!$readStatus) {
                $readStatus = new MessageReadStatus();
                $readStatus->setMessage($message);
                $readStatus->setUser($user);
                $readStatus->setCommission($message->getCommission());
            }
    
            $readStatus->setIsRead(true);
            $entityManager->persist($readStatus);
        }
    
        $entityManager->flush();
    
        return $this->json(['success' => true], Response::HTTP_OK);
    }
    
}
