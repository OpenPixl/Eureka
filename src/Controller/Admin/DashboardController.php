<?php

namespace App\Controller\Admin;

use App\Repository\Appli\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'op_admin_dashboard_home')]
    public function index(): Response
    {
        $hasAccess = $this->isGranted('ROLE_SUPER_ADMIN');
        $user = $this->getUser();

        dd($hasAccess);

        if($hasAccess == true){
            return $this->redirectToRoute('op_admin_dashboard_teacher');
        }
        else{
            return $this->redirectToRoute('op_admin_dashboard_student');
        }
    }

    #[Route('/super/dashboard', name: 'op_admin_dashboard_super')]
    public function super(): Response
    {
        return $this->render('admin/dashboard/super.html.twig');
    }

    #[Route('/admin/teacher', name: 'op_admin_dashboard_teacher')]
    public function teacher(CourseRepository $courseRepository): Response
    {
        $user = $this->getUser();
        $courses = $courseRepository->findBy(['teacher'=> $user]);
        return $this->render('admin/dashboard/teacher.html.twig',[
            'courses' => $courses,
        ]);
    }

    #[Route('/admin/student', name: 'op_admin_dashboard_student')]
    public function student(): Response
    {
        return $this->render('admin/dashboard/student.html.twig');
    }
}
