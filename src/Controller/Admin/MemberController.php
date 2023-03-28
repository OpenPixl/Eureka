<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Member;
use App\Form\Admin\MemberType;
use App\Repository\Admin\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


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
    public function new(Request $request, MemberRepository $memberRepository): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberRepository->save($member, true);

            return $this->redirectToRoute('app_admin_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/new.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/admin/member/teacher', name: 'op_admin_member_teacher', methods: ['GET', 'POST'])]
    public function teacher(Request $request, MemberRepository $memberRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $teacher = new Member();
        $form = $this->createForm(MemberType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $passwordHasher->hashPassword(
                $teacher,
                'eureka'
            );
            $teacher->setIsVerified(1);
            $teacher->setPassword($hashedPassword);
            $teacher->setTypemember('Enseignant');
            $memberRepository->save($teacher, true);

            return $this->redirectToRoute('op_admin_member_listteacher', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/member/teacher.html.twig', [
            'member' => $teacher,
            'form' => $form,
        ]);
    }

    #[Route('/studient/member/studient', name: 'op_admin_member_studient', methods: ['GET', 'POST'])]
    public function studient(Request $request, MemberRepository $memberRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $studient = new Member();
        $form = $this->createForm(MemberType::class, $studient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $passwordHasher->hashPassword(
                $studient,
                'eureka'
            );
            $studient->setIsVerified(1);
            $studient->setPassword($hashedPassword);
            $studient->setTypemember('Etudiant');
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
        return $this->render('admin/member/show.html.twig', [
            'member' => $member,
        ]);
    }

    #[Route('/admin/member/{id}/edit', name: 'op_admin_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Member $member, MemberRepository $memberRepository): Response
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
        return $this->renderForm('admin/member/edit.html.twig', [
            'member' => $member,
            'form' => $form,
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
}
