<?php

namespace App\Controller\Appli;

use App\Entity\Appli\Registration;
use App\Form\Appli\RegistrationType;
use App\Repository\Appli\BookroomRepository;
use App\Repository\Appli\RegistrationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appli/registration')]
class RegistrationController extends AbstractController
{
    #[Route('/', name: 'app_appli_registration_index', methods: ['GET'])]
    public function index(RegistrationRepository $registrationRepository): Response
    {
        return $this->render('appli/registration/index.html.twig', [
            'registrations' => $registrationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_appli_registration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $registration = new Registration();
        $form = $this->createForm(RegistrationType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($registration);
            $entityManager->flush();

            return $this->redirectToRoute('app_appli_registration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/registration/new.html.twig', [
            'registration' => $registration,
            'form' => $form,
        ]);
    }
    #[Route('/newonstudient/{idbookroom}', name: 'app_appli_registration_newonstudient', methods: ['GET', 'POST'])]
    public function newonstudient(Request $request, EntityManagerInterface $entityManager, $idbookroom, BookroomRepository $bookroomRepository): Response
    {
        $user = $this->getUser();
        //dd($user);
        $bookroom = $bookroomRepository->find($idbookroom);
        $registration = new Registration();
        $registration->setSeance($bookroom);
        $registration->setStudient($user);
        $form = $this->createForm(RegistrationType::class, $registration, [
            'action'=> $this->generateUrl('app_appli_registration_newonstudient', ['idbookroom' => $idbookroom]),
            'method'=>'POST',
            'attr' => ['id'=>'formAddRegistrationOnStudient']
        ]);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $registration->setStudient($user);
            $entityManager->persist($registration);
            $entityManager->flush();



            //return $this->redirectToRoute('app_appli_registration_index', [], Response::HTTP_SEE_OTHER);

            return $this->json([
                'code' => 200,
                'message' => "Vous êtes enregistré sur cette séance.",

            ]);
        }

        $view = $this->renderForm('appli/registration/new.html.twig', [
            'registration' => $registration,
            'bookroom' => $bookroom,
            'form' => $form
        ]);

        //dd($view->getContent());
        return $this->json([
            'code'=> 200,
            'form' => $view->getContent()
        ], 200);

        //return $this->renderForm('appli/registration/new.html.twig', [
        //    'registration' => $registration,
        //    'form' => $form,
        //]);
    }

    #[Route('/{id}', name: 'app_appli_registration_show', methods: ['GET'])]
    public function show(Registration $registration): Response
    {
        return $this->render('appli/registration/show.html.twig', [
            'registration' => $registration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appli_registration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Registration $registration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistrationType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_appli_registration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/registration/edit.html.twig', [
            'registration' => $registration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appli_registration_delete', methods: ['POST'])]
    public function delete(Request $request, Registration $registration, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$registration->getId(), $request->request->get('_token'))) {
            $entityManager->remove($registration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appli_registration_index', [], Response::HTTP_SEE_OTHER);
    }
}
