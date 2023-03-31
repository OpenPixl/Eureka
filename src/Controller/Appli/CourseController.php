<?php

namespace App\Controller\Appli;

use App\Entity\Appli\Course;
use App\Form\Appli\CourseType;
use App\Repository\Appli\BookroomRepository;
use App\Repository\Appli\CourseRepository;
use App\Repository\Appli\RegistrationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appli/course')]
class CourseController extends AbstractController
{
    #[Route('/', name: 'op_appli_course_index', methods: ['GET'])]
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('appli/course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'op_appli_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('op_appli_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'op_appli_course_show', methods: ['GET'])]
    public function show(Course $course): Response
    {
        return $this->render('appli/course/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/showforstudient/{id}', name: 'op_appli_course_showforstudient', methods: ['GET'])]
    public function showForStudient(
        Course $course,
        BookroomRepository $bookroomRepository,
        RegistrationRepository $registrationRepository
        ): Response
    {
        $user = $this->getUser();

        $now = new \DateTime('now') ;
        $now = strtotime($now->format('Y/m/d'));
        $rows = array();

        for($i = 0; $i<=9; $i++)
        {
            if(date('w',$now) == 1 ){
                $interval = new \DateInterval('P'.($i*7).'D');
                $monday = date_add(new \DateTime('now'), $interval);
                $friday = date_add(new \DateTime('now'), new \DateInterval('P'.(($i*7)+4).'D'));
                $row = array('monday' => $monday, 'friday' => $friday);
            }else{
                $interval = new \DateInterval('P'.($i*7).'D');
                $lastMonday = date('Y/m/d',strtotime('this week', $now));
                $monday = date_add(new \DateTime($lastMonday), $interval);
                $friday = date_add(new \DateTime($lastMonday), new \DateInterval('P'.(($i*7)+4).'D'));
                $row = array('monday' => $monday, 'friday' => $friday);
            }
            array_push($rows, $row);
        }
        // -------------- Bloc complémentaire à la fonction
        // Liste des séances rattachées à la matières
        $bookrooms = $bookroomRepository->findBy(['course'=> $course]);
        $seances = $bookroomRepository->seance($course->getId());
        //dd($seances);
        $registrations = array();
        foreach ($bookrooms as $bookroom){
            // Liste des réservations
            $registration = $registrationRepository->searchRegistrationByUserAndBookrooms($bookroom->getId(), $user->getId());
            if($registration){
                array_push($registrations, $registration);
            }

        }
        //dd($registrations);


        return $this->render('appli/course/showforstudient.html.twig', [
            'sems' => $rows,
            'course' => $course,
            'bookrooms' => $bookrooms,
            'seances' => $seances,
            'registrations' => $registrations
        ]);
    }

    #[Route('/{id}/edit', name: 'op_appli_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('op_appli_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/course/edit.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'op_appli_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_appli_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
