<?php

namespace App\Controller;

use App\Entity\ForgetPassword;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset/password/{token}', name: 'app_reset_password')]
    public function reset(Request $request, string $token, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $forgetPassword = $em->getRepository(ForgetPassword::class)->findOneBy(['token' => $token]);

        if (!$forgetPassword) {
            throw $this->createNotFoundException('Token not found');
        }

        // Check if token is expired (if needed)
        $expirationDate = $forgetPassword->getDate()->modify('+1 hour');
        if ($expirationDate < new \DateTime()) {
            throw $this->createNotFoundException('Token expired');
        }

        $resetForm = $this->createForm(ResetPasswordType::class);
        $resetForm->handleRequest($request);

        if ($resetForm->isSubmitted() && $resetForm->isValid()) {
            // Reset password logic
            $user = $forgetPassword->getUser();
            $newEncodedPassword = $passwordHasher->hashPassword($user, $resetForm->get('plainPassword')->getData());
            $user->setPassword($newEncodedPassword);

            $em->remove($forgetPassword);
            $em->flush();

            $this->addFlash('success', 'Your password has been reset successfully.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $resetForm->createView(),
        ]);
    }
}
