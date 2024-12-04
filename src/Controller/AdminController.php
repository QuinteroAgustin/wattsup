<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Repository\CommissionRepository;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function adminPannel(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
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

    #[Route('/admin/commissions', name: 'app_admin_commissions')]
    public function listCommissions(CommissionRepository $commissionRepository): Response
    {
        $commissions = $commissionRepository->findAll();
        return $this->render('admin/commissions.html.twig', [
            'commissions' => $commissions,
        ]);
    }
}
