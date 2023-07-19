<?php

namespace App\Controller\Admin;

use App\Repository\Appli\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'op_admin_dashboard_home')]
    public function index(): Response
    {
        $hasAccess = $this->isGranted('ROLE_SUPER_ADMIN');
        $user = $this->getUser();

        //dd($hasAccess);

        if($hasAccess == true){
            return $this->redirectToRoute('op_admin_dashboard_teacher');
        }
        else{
            return $this->redirectToRoute('op_admin_dashboard_studient');
        }
    }

    #[Route('/super/dashboard', name: 'op_admin_dashboard_super')]
    public function super(): Response
    {
        $hasAccessSuper = $this->isGranted('ROLE_SUPER_ADMIN');
        if($hasAccessSuper == true){
            return $this->render('admin/dashboard/super.html.twig');
        }
        return $this->redirectToRoute('op_public_security_login');
    }

    #[Route('/admin/teacher', name: 'op_admin_dashboard_teacher')]
    public function teacher(CourseRepository $courseRepository): Response
    {
        $user = $this->getUser();
        if($user->getTypemember() == 'Enseignant'){
            $courses = $courseRepository->findBy(['teacher'=> $user]);
            return $this->render('admin/dashboard/teacher.html.twig',[
                'courses' => $courses,
            ]);
        }
        return $this->redirectToRoute('op_public_security_login');

    }

    #[Route('/student/dashboard', name: 'op_admin_dashboard_studient')]
    public function studient(CourseRepository $courseRepository): Response
    {
        $user = $this->getUser();

        if($user->getTypemember() == 'etudiant'){
            $courses = $courseRepository->findAll();
            return $this->render('admin/dashboard/studient.html.twig',[
                'courses' => $courses,
            ]);
        }
        return $this->redirectToRoute('op_public_security_login');
    }
}
