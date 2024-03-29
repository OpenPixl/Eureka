<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Member;
use App\Form\Admin\MemberType;
use App\Repository\Admin\MemberRepository;
use App\Repository\Appli\BookroomRepository;
use App\Repository\Appli\CourseRepository;
use App\Repository\Appli\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class MemberController extends AbstractController
{
    #[Route('/admin/member', name: 'op_admin_member_index', methods: ['GET'])]
    public function index(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findAll(),
        ]);
    }

    #[Route('/admin/member/listteacher', name: 'op_admin_member_listteacher', methods: ['GET'])]
    public function listteacher(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findAll(),
        ]);
    }
    #[Route('/admin/member/liststudient', name: 'op_admin_member_liststudient', methods: ['GET'])]
    public function liststudient(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findAll(),
        ]);
    }

    #[Route('/admin/member/new', name: 'op_admin_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MemberRepository $memberRepository, SluggerInterface $slugger): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Enregistrement de l'avatar du Membre
            /** @var UploadedFile $avatarFile */
            $avatarFile = $form->get('avatarName')->getData();
            if ($avatarFile) {
                $originalavatarFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeavatarFilename = $slugger->slug($originalavatarFilename);
                $newavatarFilename = $safeavatarFilename . $avatarFile->guessExtension();
                try {
                    $avatarFile->move(
                        $this->getParameter('banniere_directory'),
                        $newavatarFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            $member->setAvatarName($newavatarFilename);

            $memberRepository->save($member, true);

            return $this->redirectToRoute('app_admin_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/new.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/admin/member/teacher', name: 'op_admin_member_newteacher', methods: ['GET', 'POST'])]
    public function teacher(Request $request, MemberRepository $memberRepository, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        $teacher = new Member();
        $form = $this->createForm(MemberType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Enregistrement de l'avatar du Membre
            /** @var UploadedFile $avatarFile */
            $avatarFile = $form->get('avatarFile')->getData();
            if ($avatarFile) {
                $originalavatarFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeavatarFilename = $slugger->slug($originalavatarFilename);
                $newavatarFilename = $safeavatarFilename .".". $avatarFile->guessExtension();
                try {
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newavatarFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            $teacher->setAvatarName($newavatarFilename);

            $hashedPassword = $passwordHasher->hashPassword(
                $teacher,
                'eureka'
            );
            $teacher->setIsVerified(1);
            $teacher->setPassword($hashedPassword);
            $teacher->setTypemember('Enseignant');
            $teacher->setRoles(["ROLE_ADMIN"]);
            $memberRepository->save($teacher, true);

            return $this->redirectToRoute('op_admin_member_listteacher', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/teacher.html.twig', [
            'member' => $teacher,
            'form' => $form,
        ]);
    }

    #[Route('/admin/member/studient', name: 'op_admin_member_newstudient', methods: ['GET', 'POST'])]
    public function studient(Request $request, MemberRepository $memberRepository, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        $studient = new Member();
        $form = $this->createForm(MemberType::class, $studient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Enregistrement de l'avatar du Membre
            /** @var UploadedFile $avatarFile */
            $avatarFile = $form->get('avatarName')->getData();
            if ($avatarFile) {
                $originalavatarFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeavatarFilename = $slugger->slug($originalavatarFilename);
                $newavatarFilename = $safeavatarFilename . $avatarFile->guessExtension();
                try {
                    $avatarFile->move(
                        $this->getParameter('banniere_directory'),
                        $newavatarFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            $studient->setAvatarName($newavatarFilename);

            $hashedPassword = $passwordHasher->hashPassword(
                $studient,
                'eureka'
            );
            $studient->setIsVerified(1);
            $studient->setPassword($hashedPassword);
            $studient->setTypemember('Etudiant');
            $studient->setRoles(["ROLE_USER"]);
            $memberRepository->save($studient, true);

            return $this->redirectToRoute('op_admin_member_liststudient', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/studient.html.twig', [
            'member' => $studient,
            'form' => $form,
        ]);
    }

    #[Route('/admin/member/{id}', name: 'app_admin_member_show', methods: ['GET'])]
    public function show(Member $member): Response
    {
        return $this->json([
            'code' => 200,
            'message' => "Action sur lme membre sélectionné",
            'actions' => $this->renderView('admin/member/show.html.twig', [
                'member' => $member,
            ])
        ]);
    }

    #[Route('/admin/member/{id}/edit', name: 'op_admin_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Member $member, MemberRepository $memberRepository, SluggerInterface $slugger): Response
    {
        $typemember = $member->getTypemember();

        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Suppression de l'avatar précedente
            $supprLogoInput = $form->get('isSupprAvatar')->getData();
            if($supprLogoInput && $supprLogoInput == true){
                // récupération du nom de l'image
                $logoName = $member->getAvatarName();
                $pathheader = $this->getParameter('avatar_directory').'/'.$logoName;
                // On vérifie si l'image existe
                if(file_exists($pathheader)){
                    unlink($pathheader);
                }
                $member->setAvatarName(null);
                $member->setIsSupprAvatar(0);
            }

            // Enregistrement de l'avatar du Membre
            /** @var UploadedFile $avatarFile */
            $avatarFile = $form->get('avatarFile')->getData();
            //dd($avatarFile);
            // Modification de l'image
            if ($avatarFile) {
                // Effacement du fichier avatar si il est présent en BDD
                // récupération du nom de l'image
                $avatarName = $member->getAvatarName();
                // suppression du Fichier
                if($avatarName){
                    $pathAvatar = $this->getParameter('logo_directory').'/'.$avatarName;
                    // On vérifie si l'image existe
                    if(file_exists($pathAvatar)){
                        unlink($pathAvatar);
                    }
                }
                $originalavatarFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeavatarFilename = $slugger->slug($originalavatarFilename);
                $newavatarFilename = $safeavatarFilename.".".$avatarFile->guessExtension();
                try {
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newavatarFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            $memberRepository->save($member, true);
            if($typemember == "Enseignant"){
                return $this->redirectToRoute('op_admin_member_listteacher', [], Response::HTTP_SEE_OTHER);
            }elseif($typemember == "Etudiant"){
                return $this->redirectToRoute('op_admin_member_liststudient', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->redirectToRoute('op_admin_member_index', [], Response::HTTP_SEE_OTHER);
            }

        }
        return $this->render('admin/member/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    /**
     * @param Request $request
     * @param Member $member
     * @param MemberRepository $memberRepository
     * @return Response
     * Edition de la fiche de membre pour les enseignants et les étudiants
     */
    #[Route('/member/{id}/edit', name: 'op_webapp_member_edit', methods: ['GET', 'POST'])]
    public function editMember(Request $request, Member $member, MemberRepository $memberRepository): Response
    {
        $typemember = $member->getTypemember();

        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberRepository->save($member, true);
            if($typemember == "Enseignant"){
                return $this->redirectToRoute('op_admin_member_listteacher', [], Response::HTTP_SEE_OTHER);
            }elseif($typemember == "Etudiant"){
                return $this->redirectToRoute('op_admin_member_liststudient', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->redirectToRoute('op_admin_member_index', [], Response::HTTP_SEE_OTHER);
            }

        }
        return $this->render('admin/member/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/studient/showcoursestudient', name: 'op_admin_member_showcoursestudient', methods: ['GET'])]
    public function ShowcourseStudient(RegistrationRepository $registrationRepository, BookroomRepository $bookroomRepository)
    {
        $user = $this->getUser();
        //$courses = $courses
        $Bookrooms = $registrationRepository->findBy(['studient' => $user]);
        //dd($Bookrooms);

        //$dateSeances = $bookroomRepository->seance($course->getId());

        return $this->renderForm('admin/member/studientCourse.html.twig', [
            'Bookrooms' => $Bookrooms,
        ]);
    }

    #[Route('/admin/member/{id}', name: 'op_admin_member_delete', methods: ['POST'])]
    public function delete(Request $request, Member $member, MemberRepository $memberRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->request->get('_token'))) {
            $memberRepository->remove($member, true);
        }

        return $this->redirectToRoute('op_admin_member_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/member/disactivated/{id}', name: 'op_admin_member_disactivated', methods: ['POST'])]
    public function disactivated(Member $member, MemberRepository $memberRepository)
    {
        $who = $member->getFirstName().' '.$member->getLastName();
        $member->setIsVerified(0);
        $memberRepository->save($member, true);

        $members = $memberRepository->findAll();

        return $this->json([
            'code' => 200,
            'message' => "Le profil du membre ".$who." a été correctement désactivé.",
            'liste' => $this->renderView('admin/member/include/_liste.html.twig', [
                'members' => $members
            ])
        ], 200);
    }
}
