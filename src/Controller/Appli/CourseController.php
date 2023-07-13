<?php

namespace App\Controller\Appli;

use App\Entity\Appli\Course;
use App\Form\Appli\CourseType;
use App\Repository\Appli\BookroomRepository;
use App\Repository\Appli\CourseRepository;
use App\Repository\Appli\RegistrationRepository;
use App\Service\timeService;
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
        RegistrationRepository $registrationRepository,
        timeService $timeService,
        ): Response
    {
        $user = $this->getUser();
        $rows = $timeService->Sems();
        // Liste des séances rattachées à la matière
        $bookrooms = $bookroomRepository->findBy(['course'=> $course], ['hourBookOpenAt' => 'ASC']);
        $seances = $bookroomRepository->seance($course->getId());

        $registrations = array();
        foreach ($bookrooms as $bookroom){
            // Liste des réservations
            $registration = $registrationRepository->searchRegistrationByUserAndBookrooms($bookroom->getId(), $user->getId());
            if($registration){
                array_push($registrations, $registration);
            }
        }

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

    #[Route('/addstudientcourse/{idmember}', name: 'op_appli_course_addstudientcourse', methods: ['POST', 'GET'])]
    public function addStudientCourse(Request $request, EntityManagerInterface $entityManager)
    {
        return $this->render('appli/course/addStudientCourse.html.twig', [

        ]);
    }
}
