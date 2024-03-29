<?php

namespace App\Controller\Appli;

use App\Entity\Appli\Bookroom;
use App\Form\Appli\BookroomType;
use App\Repository\Appli\BookroomRepository;
use App\Repository\Appli\CourseRepository;
use App\Repository\Appli\RegistrationRepository;
use App\Service\timeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


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

    #[Route('/listbyuuid/{idbookroom}', name: 'op_appli_bookroom_listbyuuid', methods: ['GET'])]
    public function selecbyuniq(BookroomRepository $bookroomRepository, $idbookroom){

        $bookroom = $bookroomRepository->bookroom($idbookroom);
        //dd($bookroom['uniq']);
        $uniqs = $bookroomRepository->listByUniq($bookroom['uniq']);
        //dd($uniqs);

        return $this->json([
            'code' => 200,
            'message' => "Le cours est ajouté",
            'bookroom' => $bookroom,
            "uniqs" => $uniqs
        ]);

    }

    #[Route('/listbycourse/{idcourse}', name: 'op_appli_bookroom_listbycourse', methods: ['GET'])]
    public function listbyCourse(BookroomRepository $bookroomRepository, $idcourse): Response
    {
        $now = new \DateTime('now');
        $now = strtotime($now->format('Y/m/d'));
        $rows = array();

        for($i = 0; $i<=40; $i++)
        {
            if(date('w',$now) == 1 ){
                $interval = new \DateInterval('P'.($i*7).'D');
                $monday = date_add(new \DateTime('now'), $interval);
                $monday->setTime(0, 0, 0);
                $friday = date_add(new \DateTime('now'), new \DateInterval('P'.(($i*7)+5).'D'));
                $friday->setTime(0, 0, 0);
                $row = array('monday' => $monday, 'friday' => $friday);
            }else{
                $interval = new \DateInterval('P'.($i*7).'D');
                $lastMonday = date('Y/m/d',strtotime('this week', $now));
                $monday = date_add(new \DateTime($lastMonday), $interval);
                $monday->setTime(0, 0, 0);
                $friday = date_add(new \DateTime($lastMonday), new \DateInterval('P'.(($i*7)+5).'D'));
                $friday->setTime(0, 0, 0);
                $row = array('monday' => $monday, 'friday' => $friday);
            }
            //dd($interval, $monday, $friday, $row);
            array_push($rows, $row);
        }
        //dd($bookroomRepository->findBy(['course'=> $idcourse]));

        return $this->render('appli/bookroom/listbycourse.html.twig', [
            'sems' => $rows,
            'bookrooms' => $bookroomRepository->findBy(['course'=> $idcourse], ['dateBookAt' => 'ASC']),
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
    public function newonteacher(
        BookroomRepository $bookroomRepository,
        CourseRepository $courseRepository,
        $idcourse,
        Request $request,
        timeService $timeService
    )
    {
        $member = $this->getUser();
        //dd($member);
        $course = $courseRepository->find($idcourse);
        //dd($course);
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
            $randomString = bin2hex(random_bytes(10)); // Convertit les octets en une chaîne hexadécimale

            $uniq = substr($randomString, 0, 20);
            //dd($uniq);
            $bookroom->setUniq($uniq);
            $bookroomRepository->add($bookroom, true);
            if($bookroom->isIsReplicate() == true){
                $number = $bookroom->getNumberOfReplicate();
                $choicetime = $bookroom->getChoiceTime();
                $firstDate = $bookroom->getDateBookAt();
                $rows = array();
                for($i = 1; $i<= $number; $i++){
                    // condition si la selection est sur récurrence en jour
                    if($choicetime == 'day')
                    {
                        // creation des dates
                        $interval = new \DateInterval('P1D');
                        $newDate = date_add($firstDate, $interval);
                    }elseif ($choicetime == 'week')
                    {
                        // creation des dates
                        $interval = new \DateInterval('P7D');
                        $newDate = date_add($firstDate, $interval);
                    }
                    // création de la nouvelle séance à partir de la nouvelle date de séance
                    $newbookroom = clone $bookroom;
                    $newbookroom->setDateBookAt($newDate);
                    $newbookroom->setIsReplicate(null);
                    $newbookroom->setNumberOfReplicate(null);
                    $newbookroom->setChoiceTime(null);
                    $bookroomRepository->add($newbookroom, true);
                }

            }
            $bookrooms = $bookroomRepository->findBy(['course'=> $course]);
            $sems = $timeService->Sems();

            return $this->json([
                'code' => 200,
                'message' => "Le cours est ajouté",
                'liste' => $this->renderView('appli/bookroom/listbycourse.html.twig', [
                    'bookrooms' => $bookrooms,
                    'sems' => $sems
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

    #[Route('/publish/{id}', name: 'op_appli_bookroom_publish', methods: ['GET', 'POST'])]
    public function publishBookroom(Bookroom $bookroom, BookroomRepository $bookroomRepository)
    {
        $isActiv = $bookroom->isIsActive();
        if($isActiv == true){
            $bookroom->setIsActive(0);
            $bookroomRepository->add($bookroom, true);
            return $this->json([
                'code' => 200,
                'message' => 'La séance a été mise à jour avec la publication mise à : '.$bookroom->isIsActive(),
                'card' => $this->renderView('appli/bookroom/include/_cardPublishBookroom.html.twig', [
                    'bookroom' => $bookroom
                ])
            ], 200);
        }else{
            $bookroom->setIsActive(1);
            $bookroomRepository->add($bookroom, true);
            return $this->json([
                'code' => 200,
                'message' => 'La séance a été mise à jour avec la publication mise à : '.$bookroom->isIsActive(),
                'card' => $this->renderView('appli/bookroom/include/_cardPublishBookroom.html.twig', [
                    'bookroom' => $bookroom
                ])
            ], 200);
        }



    }

    #[Route('/{id}', name: 'op_appli_bookroom_show', methods: ['GET'])]
    public function show(Bookroom $bookroom): Response
    {
        return $this->render('appli/bookroom/show.html.twig', [
            'bookroom' => $bookroom,
        ]);
    }

    /*
     * Affiche la séance dans une modal
     */
    #[Route('/modal/{id}', name:'op_appli_bookroom_modal', methods: ['GET'])]
    public function showOnModal(Bookroom $bookroom)
    {

        return $this->json([
            'code' => 200,
            'message' => '',
            'content' => $this->renderView('appli/bookroom/include/_modalBookroomTeacher.html.twig', [
                'bookroom' => $bookroom,
            ])
        ],200);
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
    public function editonteacher(
        Bookroom $bookroom,
        BookroomRepository $bookroomRepository,
        CourseRepository $courseRepository,
        timeService $timeService,
        Request $request
    )
    {
        $member = $this->getUser();
        //dd($bookroom);
        $course = $courseRepository->find($bookroom->getCourse());
        //dd($course);
        $form = $this->createForm(BookroomType::class, $bookroom, [
            'action'=> $this->generateUrl('op_appli_bookroom_editonteacher', ['id' => $bookroom->getId()]),
            'method'=>'POST',
            'attr' => ['id'=>'formEditBookRoomOnTeacher']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookroomRepository->add($bookroom, true);
            $sems = $timeService->Sems();
            return $this->json([
                'code' => 200,
                'message' => "Le cours est modifié",
                'liste' => $this->renderView('appli/bookroom/listbycourse.html.twig', [
                    'bookrooms' => $bookroomRepository->findBy(['course'=> $course->getId()]),
                    'sems' => $sems
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

    /**
     * Supression d'une séance depuis la page des enseignants
     */
    #[Route('/del/{id}', name: 'op_appli_bookroom_delone', methods: ['POST'])]
    public function DelOne(
        Bookroom $bookroom,
        BookroomRepository $bookroomRepository,
        CourseRepository $courseRepository,
        RegistrationRepository $registrationRepository,
        timeService $timeService,
        MailerInterface $mailer
    )
    {
        // Supression des inscriptions
        $course = $courseRepository->find($bookroom->getCourse());

        $registrations = $registrationRepository->findBy(['seance' => $bookroom->getId()]);

        if($registrations)
        {
            foreach($registrations as $registration){
                $registrationRepository->remove($registration);
                // mettre en place le mail d'annulation
                $email = (new Email())
                    ->from('contact@openpixl.fr')
                    ->to('xavier.burke@gmail.com')
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('test d\'annulation !')
                    //->text('Sending emails is fun again!')
                    ->html('<p>See Twig integration for better HTML integration!</p>');

                $mailer->send($email);
            }
        }
        // Suppression de la séance
        $bookroomRepository->remove($bookroom, true);

        // renvoie vers la page
        $sems = $timeService->Sems();
        return $this->json([
            'code'=> 200,
            'message' => 'la séance a été correctement supprimée.',
            'liste' => $this->renderView('appli/bookroom/listbycourse.html.twig', [
                'bookrooms' => $bookroomRepository->findBy(['course'=> $course->getId()]),
                'sems' => $sems
            ])
        ], 200);
    }

    /**
     * Suppression d'une séance depuis la page des enseignants
     */
    #[Route('/delall/{uniq}', name: 'op_appli_bookroom_delall', methods: ['POST'])]
    public function DelAll($uniq, BookroomRepository $bookroomRepository, RegistrationRepository $registrationRepository) : response
    {
        // Récupéretion des séances ayant la même uniq
        $uniqs = $bookroomRepository->findBy(['uniq' => $uniq]);

        foreach ($uniqs as $u){
            // Supression des inscriptions
            $inscriptions = $registrationRepository->findBy(['seance'=> $u->getId()]);
            //dd($inscriptions);
            foreach ($inscriptions as $inscr) {
                $registrationRepository->remove($inscr, true);
                // Mettre en place le mail d'information
            }
            // Suppression de la séance
            $bookroomRepository->remove($u, true);
        }

        return $this->json([], 200);
    }
}
