<?php

namespace App\Controller\Appli;

use App\Entity\Appli\Bookroom;
use App\Form\Appli\BookroomType;
use App\Repository\Appli\BookroomRepository;
use App\Repository\Appli\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appli/bookroom')]
class BookroomController extends AbstractController
{
    #[Route('/', name: 'op_appli_bookroom_index', methods: ['GET'])]
    public function index(BookroomRepository $bookroomRepository): Response
    {
        return $this->render('appli/bookroom/index.html.twig', [
            'bookrooms' => $bookroomRepository->findAll(),
        ]);
    }

    #[Route('/listbycourse/{idcourse}', name: 'op_appli_bookroom_listbycourse', methods: ['GET'])]
    public function listbyCourse(BookroomRepository $bookroomRepository, $idcourse): Response
    {
        return $this->render('appli/bookroom/listbycourse.html.twig', [
            'bookrooms' => $bookroomRepository->findBy(['course'=> $idcourse]),
        ]);
    }

    #[Route('/new', name: 'op_appli_bookroom_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bookroom = new Bookroom();
        $form = $this->createForm(BookroomType::class, $bookroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bookroom);
            $entityManager->flush();

            return $this->redirectToRoute('op_appli_bookroom_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/bookroom/new.html.twig', [
            'bookroom' => $bookroom,
            'form' => $form,
        ]);
    }

    #[Route('/newonteacher/{idcourse}', name: 'op_appli_bookroom_newonteacher', methods: ['GET', 'POST'])]
    public function newonteacher(BookroomRepository $bookroomRepository, CourseRepository $courseRepository, $idcourse, Request $request)
    {
        $member = $this->getUser();
        $course = $courseRepository->find($idcourse);
        // création de l'entité
        $bookroom = new Bookroom();
        $bookroom->setTeacher($member);
        $bookroom->setCourse($course);

        $form = $this->createForm(BookroomType::class, $bookroom, [
            'action'=> $this->generateUrl('op_appli_bookroom_newonteacher', ['idcourse' => $idcourse]),
            'method'=>'POST',
            'attr' => ['id'=>'formAddBookRoomOnTeacher']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bookroomRepository->add($bookroom, true);
            $bookrooms = $bookroomRepository->findBy(['course'=> $course]);

            return $this->json([
                'code' => 200,
                'message' => "Le cours est ajouté",
                'liste' => $this->renderView('appli/bookroom/listbycourse.html.twig', [
                    'bookrooms' => $bookrooms,
                ])
            ]);
        }

        if($form->isSubmitted()){
            dd($form->get('dateBookAt')->getData());
        }

        $view = $this->renderForm('appli/bookroom/_form.html.twig', [
            'bookroom' => $bookroom,
            'form' => $form
        ]);

        //dd($view->getContent());
        return $this->json([
            'code'=> 200,
            'form' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'op_appli_bookroom_show', methods: ['GET'])]
    public function show(Bookroom $bookroom): Response
    {
        return $this->render('appli/bookroom/show.html.twig', [
            'bookroom' => $bookroom,
        ]);
    }

    #[Route('/{id}/edit', name: 'op_appli_bookroom_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bookroom $bookroom, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookroomType::class, $bookroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('op_appli_bookroom_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/bookroom/edit.html.twig', [
            'bookroom' => $bookroom,
            'form' => $form,
        ]);
    }

    #[Route('/editonteacher/{id}', name: 'op_appli_bookroom_editonteacher', methods: ['GET', 'POST'])]
    public function editonteacher(Bookroom $bookroom, BookroomRepository $bookroomRepository, CourseRepository $courseRepository, Request $request)
    {
        $member = $this->getUser();
        $course = $courseRepository->find($bookroom->getId());
        $form = $this->createForm(BookroomType::class, $bookroom, [
            'action'=> $this->generateUrl('op_appli_bookroom_editonteacher', ['id' => $bookroom->getId()]),
            'method'=>'POST',
            'attr' => ['id'=>'formEditBookRoomOnTeacher']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bookroomRepository->add($bookroom, true);
            $bookrooms = $bookroomRepository->findBy(['course'=> $course]);

            return $this->json([
                'code' => 200,
                'message' => "Le cours est ajouté",
                'liste' => $this->renderView('appli/bookroom/listbycourse.html.twig', [
                    'bookrooms' => $bookrooms,
                ])
            ]);
        }

        $view = $this->renderForm('appli/bookroom/_form.html.twig', [
            'bookroom' => $bookroom,
            'form' => $form
        ]);

        //dd($view->getContent());
        return $this->json([
            'code'=> 200,
            'form' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'op_appli_bookroom_delete', methods: ['POST'])]
    public function delete(Request $request, Bookroom $bookroom, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookroom->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bookroom);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_appli_bookroom_index', [], Response::HTTP_SEE_OTHER);
    }
}
