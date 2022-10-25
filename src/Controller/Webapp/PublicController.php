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
        return $this->redirectToRoute('op_webapp_public_home');
    }

    #[Route('/home', name: 'op_webapp_public_home')]
    public function home(): Response
    {
        return $this->render('webapp/public/index.html.twig');
    }
}
