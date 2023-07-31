<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerController extends AbstractController
{
    #[Route('/admin/mailer', name: 'op_admin_mailer_test')]
    public function index(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to(new Address('xavier.burke@gmail.com'))
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->htmlTemplate('emails/signup.html.twig')
            ;

        $mailer->send($email);

        return $this->redirectToRoute('op_admin_dashboard_super');
    }

    #[Route('/admin/mailer', name: 'op_admin_mailer_test')]
    public function MailDellRegistration(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('xavier.burke@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('[Eureka-MDM] Annulation / report de Séances')
            ->htmlTemplate('emails/signup.html.twig')
        ;

        $mailer->send($email);

        return $this->redirectToRoute('op_admin_dashboard_super');
    }
}
