<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'op_admin_dashboard_home')]
    public function index(): Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
    #[Route('/super/dashboard', name: 'op_admin_dashboard_super')]
    public function super(): Response
    {
        return $this->render('admin/dashboard/super.html.twig');
    }
}
