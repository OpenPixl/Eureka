<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Member;
use App\Form\Admin\MemberType;
use App\Repository\Admin\MemberRepository;
use App\Repository\Appli\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/member')]
class MemberController extends AbstractController
{
    #[Route('/', name: 'op_admin_member_index', methods: ['GET'])]
    public function index(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'op_admin_member_liststudient', methods: ['GET'])]
    public function listStudient(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findBy(['roles'=> '']),
        ]);
    }

    #[Route('/{idTeacher}', name: 'op_admin_member_listcourses', methods: ['POST'])]
    public function ListCourses($idTeacher, CourseRepository $courseRepository): Response
    {
        return $this->render('admin/member/listcourse.html.twig', [
            'courses' => $courseRepository->findBy(['teacher'=> $idTeacher]),
        ]);
    }

    #[Route('/new', name: 'op_admin_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, MemberRepository $memberRepository ): Response
    {
        $member = new Member();
        $member->setMobile('00.00.00.00.00');
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // hash du mot de passe
            $member->setPassword(
                $userPasswordHasher->hashPassword(
                    $member,
                    $form->get('Password')->getData()
                )
            );
            $memberRepository->add($member, true);

            return $this->redirectToRoute('op_admin_member_liststudient', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/new.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/super/teacher', name: 'op_admin_member_teacher', methods: ['GET', 'POST'])]
    public function teacher(Request $request, MemberRepository $memberRepository, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger): Response
    {
        $member = new Member();
        $member->setRoles(array('ROLE_ADMIN'));
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // hasher le mot de passe
            $member->setPassword(
                $userPasswordHasher->hashPassword(
                    $member,
                    $form->get('Password')->getData()
                )
            );
            $memberRepository->add($member, true);

                /** @var UploadedFile $avatarFile */
                $avatarFileInput = $form->get('avatarFile')->getData();
                $logoFileInput = $form->get('logoFile')->getData();

            // Ajout de la nouvelle bannière
            $originalavatarFilename = pathinfo($avatarFileInput->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeavatarFilename = $slugger->slug($originalavatarFilename);
            $newavatarFilename = $safeavatarFilename . '-' . uniqid() . '.' . $avatarFileInput->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $avatarFileInput->move(
                    $this->getParameter('member_directory'),
                    $newavatarFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $member->setavatarName($newavatarFilename);

            $memberRepository->add($member, true);

            $this->addFlash('success', "L'enseignant a été correctement ajouté.<br>Il doit vérifier et valider son adresse mail.");
            return $this->redirectToRoute('op_admin_dashboard_super', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/teacher.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/super/studient', name: 'op_admin_member_studient', methods: ['GET', 'POST'])]
    public function studient(Request $request, MemberRepository $studientRepository, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger): Response
    {
        $studient = new Member();
        $studient->setRoles(array('ROLE_USER'));
        $form = $this->createForm(MemberType::class, $studient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // hasher le mot de passe
            $studient->setPassword(
                $userPasswordHasher->hashPassword(
                    $studient,
                    $form->get('Password')->getData()
                )
            );
            $studientRepository->add($studient, true);

            /** @var UploadedFile $avatarFile */
            $avatarFileInput = $form->get('avatarFile')->getData();

            // Ajout de la nouvelle bannière
            $originalavatarFilename = pathinfo($avatarFileInput->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeavatarFilename = $slugger->slug($originalavatarFilename);
            $newavatarFilename = $safeavatarFilename . '-' . uniqid() . '.' . $avatarFileInput->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $avatarFileInput->move(
                    $this->getParameter('member_directory'),
                    $newavatarFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $studient->setavatarName($newavatarFilename);

            $studientRepository->add($studient, true);


            $this->addFlash('success', "L'apprenant a été correctement ajouté.<br>Il doit vérifier et valider son adresse mail.");
            return $this->redirectToRoute('op_admin_dashboard_super', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/studient.html.twig', [
            'studient' => $studient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'op_admin_member_show', methods: ['GET'])]
    public function show(Member $member): Response
    {
        return $this->render('admin/member/show.html.twig', [
            'member' => $member,
        ]);
    }

    #[Route('/{id}/edit', name: 'op_admin_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Member $member, MemberRepository $memberRepository, SluggerInterface $slugger): Response
    {
        //dd($member);
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        //dd($form);
        if ($form->isSubmitted() && $form->isValid()) {

            $avatarFileInput = $form->get('AvatarFile')->getData();

            if ($avatarFileInput) {
                // Effacement du fichier bannièreFileName si il est présent en BDD
                // récupération du nom de l'image
                $avatarName = $member->getAvatarName();
                // suppression du Fichier
                if($avatarName){
                    $pathavatar = $this->getParameter('member_directory').'/'.$avatarName;
                    // On vérifie si l'image existe
                    if(file_exists($pathavatar)){
                        unlink($pathavatar);
                    }
                }
                // Ajout de la nouvelle bannière
                $originalavatarFilename = pathinfo($avatarFileInput->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeavatarFilename = $slugger->slug($originalavatarFilename);
                $newavatarFilename = $safeavatarFilename . '-' . uniqid() . '.' . $avatarFileInput->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $avatarFileInput->move(
                        $this->getParameter('member_directory'),
                        $newavatarFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $member->setavatarName($newavatarFilename);
                $memberRepository->add($member, true);
            }


            return $this->redirectToRoute('op_admin_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'op_admin_member_delete', methods: ['POST'])]
    public function delete(Request $request, Member $member, MemberRepository $memberRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->request->get('_token'))) {
            $memberRepository->remove($member, true);
        }

        return $this->redirectToRoute('op_admin_member_index', [], Response::HTTP_SEE_OTHER);
    }

}
