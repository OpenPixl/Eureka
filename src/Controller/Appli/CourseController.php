<?php

namespace App\Controller\Appli;

use App\Entity\Appli\Course;
use App\Form\Appli\CourseType;
use App\Repository\Appli\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appli/course')]
class CourseController extends AbstractController
{
    #[Route('/', name: 'app_appli_course_index', methods: ['GET'])]
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('appli/Course/index.html.twig', [
            'course' => $courseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_appli_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CourseRepository $courseRepository): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRepository->add($course, true);

            return $this->redirectToRoute('app_appli_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/teacher/{id}', name: 'app_appli_course_showteacher', methods: ['GET'])]
    public function showTeacher(Course $course): Response
    {
        return $this->render('appli/course/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/studiant/{id}', name: 'app_appli_course_showstudient', methods: ['GET'])]
    public function showStudient(Course $course): Response
    {
        return $this->render('appli/course/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appli_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, CourseRepository $CourseRepository): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $CourseRepository->add($course, true);

            return $this->redirectToRoute('app_appli_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/course/edit.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appli_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, CourseRepository $CourseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $CourseRepository->remove($course, true);
        }

        return $this->redirectToRoute('app_appli_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
