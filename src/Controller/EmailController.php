<?php

namespace App\Controller;

use Twig\Environment;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(): Response
    {
        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }

    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(MailerInterface $mailer, Environment $twig): Response
    {
        // Rendering the email content
        $htmlContent = $twig->render('email/email.html.twig', [
            'name' => 'John Doe',
        ]);

        // Creating the email
        $email = (new Email())
            ->from('noreply@agweco.fr')
            ->to('agust.quintero@gmail.com')
            ->subject('Test Email')
            ->html($htmlContent);

        try {
            // Sending the email
            $mailer->send($email);
            $message = 'Email sent successfully!';
        } catch (TransportExceptionInterface $e) {
            $message = 'Failed to send email: ' . $e->getMessage();
        }

        return new Response($message);
    }
}
