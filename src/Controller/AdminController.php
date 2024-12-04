<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use App\Repository\CommissionRepository;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function dashboard(
        CommissionRepository $commissionRepository,
        MessageRepository $messageRepository,
        UserRepository $userRepository
    ): Response {
        $totalCommissions = $commissionRepository->count([]);
        $totalMessages = $messageRepository->count([]);
        $totalUsers = $userRepository->count([]);

        return $this->render('admin/dashboard.html.twig', [
            'totalCommissions' => $totalCommissions,
            'totalMessages' => $totalMessages,
            'totalUsers' => $totalUsers,
        ]);
    }
    #[Route('/admin/users', name: 'app_admin_users')]
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/edit/{id}', name: 'app_admin_edit_user')]
    public function editUser(int $id, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Traitement du formulaire
        if ($request->isMethod('POST')) {
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));
            $user->setEmail($request->request->get('email'));
            $user->setRoles([$request->request->get('role')]);

            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès.');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit_user.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/admin/users/delete/{id}', name: 'app_admin_delete_user', methods: ['POST'])]
    public function deleteUser(
        int $id, 
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        // Récupérer l'utilisateur à partir de l'ID
        $user = $userRepository->find($id);
        
        // Si l'utilisateur n'existe pas, rediriger vers la liste avec un message d'erreur
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('app_admin');
        }
    
        // Suppression de l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();
        
        // Message de succès
        $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        
        return $this->redirectToRoute('app_admin');
    }
    
    
    #[Route('/admin/commissions', name: 'app_admin_commissions')]
    public function listCommissions(CommissionRepository $commissionRepository): Response
    {
        $commissions = $commissionRepository->findAll();
        return $this->render('admin/commissions.html.twig', [
            'commissions' => $commissions,
        ]);
    }  
    
    #[Route('/admin/commissions/edit/{id}', name: 'app_admin_edit_commission')]
    public function editCommission(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        CommissionRepository $commissionRepository,
        UserRepository $userRepository
    ): Response {
        $commission = $commissionRepository->find($id);

        if (!$commission) {
            throw $this->createNotFoundException('Commission non trouvée.');
        }

        $users = $userRepository->findAll();

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $authorId = $request->request->get('author');
            $createdAt = $request->request->get('created_at');
            $closedAt = $request->request->get('closed_at');

            $commission->setName($name);

            if ($authorId) {
                $author = $userRepository->find($authorId);
                $commission->setAuthor($author);
            } else {
                $commission->setAuthor(null);
            }

            if ($createdAt) {
                $commission->setCreatedAt(new \DateTime($createdAt));
            }

            if ($closedAt) {
                $commission->setClosedAt(new \DateTime($closedAt));
            } else {
                $commission->setClosedAt(null);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Commission mise à jour avec succès.');

            return $this->redirectToRoute('app_admin_commissions');
        }

        return $this->render('admin/edit_commission.html.twig', [
            'commission' => $commission,
            'users' => $users,
        ]);
    }   

    #[Route('/admin/commissions/delete/{id}', name: 'app_admin_delete_commission', methods: ['POST'])]
    public function deleteCommission(
        int $id, 
        CommissionRepository $commissionRepository, 
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        // Récupérer l'utilisateur à partir de l'ID
        $commissions = $commissionRepository->find($id);
        
        // Si l'utilisateur n'existe pas, rediriger vers la liste avec un message d'erreur
        if (!$commissions) {
            $this->addFlash('error', 'Commission non trouvé.');
            return $this->redirectToRoute('app_admin_commissions');
        }
    
        // Suppression de l'utilisateur
        $entityManager->remove($commissions);
        $entityManager->flush();
        
        // Message de succès
        $this->addFlash('success', 'Commission supprimé avec succès.');
        
        return $this->redirectToRoute('app_admin_commissions');
    }
}