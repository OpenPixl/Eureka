<?php

namespace App\Controller\Appli;

use App\Entity\Admin\Member;
use App\Entity\Appli\Course;
use App\Form\Admin\MemberType;
use App\Form\Appli\CourseType;
use App\Repository\Appli\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/appli/course')]
class CourseController extends AbstractController
{
    #[Route('/', name: 'op_appli_course_index', methods: ['GET'])]
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('appli/course/index.html.twig', [
            'course' => $courseRepository->findAll(),
        ]);
    }

    #[Route('/super/new', name: 'op_appli_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CourseRepository $courseRepository, SluggerInterface $slugger): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Code d'ajout d'une image dans l'entité
            // -----------------------------
            // 1. On récupére le fichier depuis le formulaire
            $logoFileInput = $form->get('logo')->getData();
            if ($logoFileInput) {
                // 2. On récupére le nom initial du fichier téléchargé
                $originallogoFilename = pathinfo($logoFileInput->getClientOriginalName(), PATHINFO_FILENAME);
                // 3. On encode les caractères particuliers en miniscule avec SluggerInterface et on créée un nouveau nom de fichier
                $safelogoFilename = $slugger->slug($originallogoFilename);
                $newlogoFilename = $safelogoFilename . '-' . uniqid() . '.' . $logoFileInput->guessExtension();
                // 4. on déplace le fichier du dossier Temp vers le dossier du Site
                try {
                    $logoFileInput->move(
                        $this->getParameter('course_directory'),
                        $newlogoFilename
                    );
                } catch (FileException $e) {
                    // ... en cas d'erreur
                }
                // 5. on hydrate l'entité avec le nom du fichier déplacé.
                $course->setLogoName($newlogoFilename);
            }

            $courseRepository->add($course, true);
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

    #[Route('/{id}/edit', name: 'op_appli_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, CourseRepository $CourseRepository): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $CourseRepository->add($course, true);

            return $this->redirectToRoute('op_appli_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/course/edit.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'op_appli_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, CourseRepository $CourseRepository): Response
    {
        // Effacement du fichier bannièreFileName si il est présent en BDD
        // récupération du nom de l'image
        $logoName = $course->getLogoName();
        // suppression du Fichier
        if($logoName){
            $pathlogo = $this->getParameter('course_directory').'/'.$logoName;
            // On vérifie si l'image existe
            if(file_exists($pathlogo)){
                unlink($pathlogo);
            }
        }

        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $CourseRepository->remove($course, true);
        }

        return $this->redirectToRoute('op_appli_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
