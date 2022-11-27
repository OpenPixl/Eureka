<?php

namespace App\Controller\Appli;

use App\Entity\Appli\Bookroom;
use App\Form\Appli\BookroomType;
use App\Repository\Appli\BookroomRepository;
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

    #[Route('/new', name: 'op_appli_bookroom_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookroomRepository $bookroomRepository): Response
    {
        $bookroom = new Bookroom();
        $form = $this->createForm(BookroomType::class, $bookroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookroomRepository->add($bookroom, true);

            return $this->redirectToRoute('op_appli_bookroom_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/bookroom/new.html.twig', [
            'bookroom' => $bookroom,
            'form' => $form,
        ]);
    }

    #[Route('/modalnew', name: 'op_appli_bookroom_modalnew', methods: ['GET', 'POST'])]
    public function modalnew(Request $request, BookroomRepository $bookroomRepository): Response
    {
        $bookroom = new Bookroom();
        $form = $this->createForm(BookroomType::class, $bookroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookroomRepository->add($bookroom, true);

            return $this->redirectToRoute('op_appli_bookroom_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/bookroom/modalnew.html.twig', [
            'bookroom' => $bookroom,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'op_appli_bookroom_show', methods: ['GET'])]
    public function show(Bookroom $bookroom): Response
    {
        return $this->render('appli/bookroom/show.html.twig', [
            'bookroom' => $bookroom,
        ]);
    }

    #[Route('/{id}/edit', name: 'op_appli_bookroom_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bookroom $bookroom, BookroomRepository $bookroomRepository): Response
    {
        $form = $this->createForm(BookroomType::class, $bookroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookroomRepository->add($bookroom, true);

            return $this->redirectToRoute('op_appli_bookroom_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/bookroom/edit.html.twig', [
            'bookroom' => $bookroom,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'op_appli_bookroom_delete', methods: ['POST'])]
    public function delete(Request $request, Bookroom $bookroom, BookroomRepository $bookroomRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookroom->getId(), $request->request->get('_token'))) {
            $bookroomRepository->remove($bookroom, true);
        }

        return $this->redirectToRoute('op_appli_bookroom_index', [], Response::HTTP_SEE_OTHER);
    }
}
