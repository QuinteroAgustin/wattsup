<?php

namespace App\Controller;

use App\Entity\User;
use Ramsey\Uuid\Uuid;
use App\Entity\ForgetPassword;
use App\Form\ForgetPasswordType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Model\ForgetPasswordRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForgetPasswordController extends AbstractController
{
    #[Route('/forget/password', name: 'app_forget_password')]
    public function index(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $forgetPasswordRequest = new ForgetPasswordRequest();
        $form = $this->createForm(ForgetPasswordType::class, $forgetPasswordRequest);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Find the user by email
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
                    ->from('noreply@yourdomain.com')
                    ->to($forgetPasswordRequest->email)
                    ->subject('Password Reset Request')
                    ->html('<p>Click the link to reset your password: <a href="' . $this->generateUrl('app_reset_password', ['token' => $forgetPassword->getToken()], UrlGeneratorInterface::ABSOLUTE_URL) . '">Reset Password</a></p>');
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
