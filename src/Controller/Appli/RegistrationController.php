<?php

namespace App\Controller\Appli;

use App\Entity\Appli\Registration;
use App\Form\Appli\RegistrationType;
use App\Repository\Appli\RegistrationRepository;
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
    public function new(Request $request, RegistrationRepository $registrationRepository): Response
    {
        $registration = new Registration();
        $form = $this->createForm(RegistrationType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrationRepository->add($registration, true);

            return $this->redirectToRoute('app_appli_registration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/registration/new.html.twig', [
            'registration' => $registration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appli_registration_show', methods: ['GET'])]
    public function show(registration $registration): Response
    {
        return $this->render('appli/registration/show.html.twig', [
            'registration' => $registration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appli_registration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, registration $registration, RegistrationRepository $registrationRepository): Response
    {
        $form = $this->createForm(RegistrationType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrationRepository->add($registration, true);

            return $this->redirectToRoute('app_appli_registration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appli/registration/edit.html.twig', [
            'registration' => $registration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appli_registration_delete', methods: ['POST'])]
    public function delete(Request $request, Registration $registration, RegistrationRepository $registrationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$registration->getId(), $request->request->get('_token'))) {
            $registrationRepository->remove($registration, true);
        }

        return $this->redirectToRoute('app_appli_registration_index', [], Response::HTTP_SEE_OTHER);
    }
}
