<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Member;
use App\Form\Admin\MemberType;
use App\Repository\Admin\MemberRepository;
use App\Repository\Admin\MessageRepository;
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

#[Route('')]
class MemberController extends AbstractController
{
    #[Route('/super/member/', name: 'op_admin_member_index', methods: ['GET'])]
    public function index(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findAll(),
        ]);
    }

    #[Route('/super/member/listteacher', name: 'op_admin_member_listteacher', methods: ['GET'])]
    public function listTeacher(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findBy(['typemember'=> 'Enseignant.e']),
        ]);
    }

    #[Route('/super/member/liststudent', name: 'op_admin_member_liststudent', methods: ['GET'])]
    public function liststudent(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findBy(['typemember'=> 'Etudiant.e']),
        ]);
    }

    #[Route('/super/member/{idTeacher}', name: 'op_admin_member_listcourses', methods: ['POST'])]
    public function ListCourses($idTeacher, CourseRepository $courseRepository): Response
    {
        return $this->render('admin/member/listcourse.html.twig', [
            'courses' => $courseRepository->findBy(['teacher'=> $idTeacher]),
        ]);
    }

    #[Route('/super/member/new', name: 'op_admin_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, MemberRepository $memberRepository ): Response
    {
        $member = new Member();
        $member->setMobile('00.00.00.00.00');
        $member->setPassword('eureka');
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // hash du mot de passe
            $member->setPassword(
                $userPasswordHasher->hashPassword(
                    $member,
                    $form->get('password')->getData()
                )
            );
            $memberRepository->add($member, true);

            return $this->redirectToRoute('op_admin_member_liststudent', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/new.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/super/member/teacher', name: 'op_admin_member_newteacher', methods: ['GET', 'POST'])]
    public function teacher(Request $request, MemberRepository $memberRepository, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger): Response
    {
        $member = new Member();
        $member->setRoles(array('ROLE_ADMIN'));
        $member->setTypemember('Enseignant.e');
        $password = 'eureka';
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // hasher le mot de passe
            $hashpassword = $userPasswordHasher->hashPassword(
                $member,
                $password
            );
            $member->setPassword($hashpassword);

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

    #[Route('/super/member/student', name: 'op_admin_member_newstudent', methods: ['GET', 'POST'])]
    public function student(Request $request, MemberRepository $studentRepository, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger): Response
    {
        $student = new Member();
        $student->setRoles(array('ROLE_USER'));
        $student->setTypemember('Etudiant.e');
        $password = 'eureka';
        $form = $this->createForm(MemberType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // hasher le mot de passe
            $hashstudentpassword = $userPasswordHasher->hashPassword(
                $student,
                $password
            );
            $student->setPassword($hashstudentpassword);
            $studentRepository->add($student, true);

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
            $student->setavatarName($newavatarFilename);

            $studentRepository->add($student, true);


            $this->addFlash('success', "L'apprenant a été correctement ajouté.<br>Il doit vérifier et valider son adresse mail.");
            return $this->redirectToRoute('op_admin_dashboard_super', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/student.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/super/member//{id}', name: 'op_admin_member_show', methods: ['GET'])]
    public function show(Member $member): Response
    {
        return $this->render('admin/member/show.html.twig', [
            'member' => $member,
        ]);
    }

    #[Route('/super/member//{id}', name: 'op_admin_member_showteacher', methods: ['GET'])]
    public function showTeacher(Member $member): Response
    {
        return $this->render('admin/member/show.html.twig', [
            'member' => $member,
        ]);
    }

    #[Route('/super/member//{id}', name: 'op_admin_member_showstudent', methods: ['GET'])]
    public function showstudent(Member $member): Response
    {
        return $this->render('admin/member/show.html.twig', [
            'member' => $member,
        ]);
    }

    #[Route('/super/member//{id}/edit', name: 'op_admin_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Member $member, MemberRepository $memberRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Code d'ajout d'une image dans l'entité
            // -----------------------------
            // 1. On récupére le fichier depuis le formulaire
            $avatarFileInput = $form->get('avatarFile')->getData();
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

    #[Route('/super/member//{id}', name: 'op_admin_member_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Member $member,
        MemberRepository $memberRepository,
        MessageRepository $messageRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->request->get('_token'))) {
            // Retirer les messages
            $messages = $memberRepository->findBy(['message' => $member]);
            foreach ($messages as $message){
                $member->removeReceivers($message);
            }
            // Retirer les cours
            $courses = $member->getCourse();
            foreach ($courses as $cours){
                $member->removeCour($cours);
            }

            $memberRepository->remove($member, true);
        }

        return $this->redirectToRoute('op_admin_member_index', [], Response::HTTP_SEE_OTHER);
    }

}
