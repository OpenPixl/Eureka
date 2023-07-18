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

    #[Route('/bookroom/{idbookroom}', name: 'app_appli_registration_listbybookroom', methods: ['GET'])]
    public function listByBookroom(RegistrationRepository $registrationRepository, $idbookroom,BookroomRepository $bookroomRepository): Response
    {
        $bookroom = $bookroomRepository->find($idbookroom);

        return $this->json([
            'code' => 200,
            'message' => 'Ok',
            'liste' => $this->renderView('appli/registration/index.html.twig', [
                'registrations' => $registrationRepository->findBy(['seance' => $bookroom->getId()]),
            ])
        ],200);
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
    public function newonstudient(Request $request, EntityManagerInterface $entityManager, $idbookroom, BookroomRepository $bookroomRepository, RegistrationRepository $registrationRepository): Response
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

            $registrations = array();
            $registration = $registrationRepository->searchRegistrationByUserAndBookrooms($bookroom->getId(), $user->getId());
            if($registration){
                array_push($registrations, $registration);
            }
            //dd($registrations);

            return $this->json([
                'code' => 200,
                'message' => "Vous êtes enregistré sur cette séance.",
                'idbookroom' => $bookroom->getId(),
                'button' => $this->renderView('appli/registration/include/_buttonRegistration.html.twig', [
                    'b' => $bookroom,
                    'registrations' =>$registrations
                ])
            ]);
        }

        $view = $this->renderForm('appli/registration/new.html.twig', [
            'registration' => $registration,
            'bookroom' => $bookroom,
            'form' => $form
        ]);

        return $this->json([
            'code'=> 200,
            'form' => $view->getContent()
        ], 200);

    }

    #[Route('/dellonstudient/{id}', name: 'app_appli_registration_dellonstudient', methods: ['GET', 'POST'])]
    public function dellonstudient(Registration $registration, EntityManagerInterface $entityManager, BookroomRepository $bookroomRepository, RegistrationRepository $registrationRepository, Request $request, ): Response
    {
        $user = $this->getUser();
        $idbookroom = $registration->getSeance();
        $bookroom = $bookroomRepository->find($idbookroom);
        // Controle des 15 jours + création de la variable de condition.
        $now = new \DateTime('now') ;
        $twoweekbefore = date_diff($bookroom->getDateBookAt(), $now);
        if ($twoweekbefore->days < 15) { $AuthDell = 0; } else { $AuthDell = 1; }

        $form = $this->createForm(RegistrationType::class, $registration, [
            'action'=> $this->generateUrl('app_appli_registration_dellonstudient', ['id' => $registration->getId()]),
            'method'=>'POST',
            'attr' => ['id'=>'formDellRegistrationOnStudient']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($registration);
            $entityManager->flush();

            $registrations = $registrationRepository->findBy(['seance' => $bookroom->getId()]);

            //dd($registrations);

            return $this->json([
                'code' => 200,
                'message' => "Votre désinscription a été prise en compte.",
                'idbookroom' => $bookroom->getId(),
                'button' => $this->renderView('appli/registration/include/_buttonRegistration.html.twig', [
                    'b' => $bookroom,
                    'registrations' =>$registrations
                ])
            ]);
        }
        $view = $this->renderForm('appli/registration/new.html.twig', [
            'registration' => $registration,
            'bookroom' => $bookroom,
            'form' => $form,
            'AuthDell' => $AuthDell,
        ]);


        return $this->json([
            'code' => 200,
            'AuthDell' => $AuthDell,
            'form' => $view->getContent()
        ]);
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
