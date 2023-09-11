<?php

namespace App\Controller\Webapp;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    #[Route('/', name: 'op_webapp_public')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->redirectToRoute('op_webapp_public_home');
    }

    #[Route('/home', name: 'op_webapp_public_home')]
    public function home(): Response
    {
        $user = $this->getUser();
        $type = $user->getTypemember();
        //dd($type);
        if($type == 'Administrateur'){
            return $this->redirectToRoute('op_admin_dashboard_super');
        }
        else if($type == 'Enseignant'){
            return $this->redirectToRoute('op_admin_dashboard_teacher');
        }
        else if($type == 'Etudiant'){
            return $this->redirectToRoute('op_admin_dashboard_studient');
        }
        return $this->redirectToRoute('op_public_security_login');
    }
}