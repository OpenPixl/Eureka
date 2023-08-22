<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'op_public_security_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // retour si erreur de connexion
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupération du denier "username" de l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'op_public_security_logout', methods: ['GET'])]
    public function logout(Security $security)
    {
        /// logout the user in on the current firewall
        $response = $security->logout();
        // you can also disable the csrf logout
        $response = $security->logout(false);

        return $this->redirectToRoute('op_webapp_public_home');
    }
}
