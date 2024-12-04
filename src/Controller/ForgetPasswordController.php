<?php

namespace App\Controller;

use App\Entity\User;
use Ramsey\Uuid\Uuid;
use App\Entity\ForgetPassword;
use App\Form\ForgetPasswordType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Model\ForgetPasswordRequest;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForgetPasswordController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/forget/password', name: 'app_forget_password')]
    public function index(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        // Si l'utilisateur est déjà authentifié, le rediriger vers la page de chat
        if ($this->security->getUser()) {
            return new RedirectResponse($this->generateUrl('app_chat'));
        }

        $forgetPasswordRequest = new ForgetPasswordRequest();
        $form = $this->createForm(ForgetPasswordType::class, $forgetPasswordRequest);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Find the User by email
            $user = $em->getRepository(User::class)->findOneBy(['email' => $forgetPasswordRequest->email]);

            if ($user) {
                $forgetPassword = new ForgetPassword();
                $forgetPassword->setUser($user);
                $forgetPassword->setToken(Uuid::uuid4()->toString());
                $forgetPassword->setDate(new \DateTime());

                $em->persist($forgetPassword);
                $em->flush();

                // Send email
                $email = (new Email())
                    ->from('noreply@agweco.fr')
                    ->to($forgetPasswordRequest->email)
                    ->subject('Password Reset Request')
                    ->html('<h1>WattSup</h1><br><p>Réinitialiser votre mot de passe ici : <a href="' . $this->generateUrl('app_reset_password', ['token' => $forgetPassword->getToken()], UrlGeneratorInterface::ABSOLUTE_URL) . '">Réinitialiser</a></p><br><p>Bien a vous l\'équipe de WattSup</p>');
                    //->html('<p>Click the link to reset your password: <a href="#">Reset Password</a></p>');

                $mailer->send($email);
            }

            $this->addFlash('success', 'If an account with that email exists, you will receive an email with a reset link.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('forget_password/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
