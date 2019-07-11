<?php

namespace App\Front\Controller;
use DateTime;
use App\Common\Entity\Task;
use App\Front\Form\TaskType;
use App\Service\EmailGenerator;
use App\Common\Repository\UserTaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class TaskController
 * @package App\Front\Controller
 */
class TaskController extends AbstractController
{
    /**
     * @param Request $request
     * @param EmailGenerator $message
     * @return Response
     */
    public function newTaskFront(Request $request, EmailGenerator $message): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task, ['user' => $this->getUser()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            foreach ($task->getUserTasks()->getValues() as $value) {
                $message->sendEmail('test sujet', $this->render('emails/NewTask.html.twig', ['task' => $task, 'usertask' => $value]), $value->getUser()->getEmail());

            }

            return $this->redirectToRoute('user_index');
        }

        return $this->render('front/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Task $task
     * @param $usertask
     * @param UserTaskRepository $UserTaskRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function changeApprove(Request $request, Task $task, $usertask, UserTaskRepository $UserTaskRepository)
    {
        $form1 = $this->createFormBuilder()
            ->add('isApproved', ChoiceType::class, [
                'choices' => [
                    'Maybe' => null,
                    'Yes' => true,
                    'No' => false,
                ],
            ])
            ->add('send', SubmitType::class)
            ->getForm();

        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            dump($usertask);
            $x = $UserTaskRepository->find($usertask);
            $x->setIsApproved($form1['isApproved']->getData());
            dump($task);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($x);
            $entityManager->flush();
            return $this->redirectToRoute('user_index');

        }
        return $this->render('emails/approve.html.twig', [
            'form1' => $form1->createView(),
            'task' => $task,

        ]);

    }


    /**
     * @param $day
     * @param $month
     * @param $year
     * @param Request $request
     * @param EmailGenerator $message
     * @return Response
     * @throws \Exception
     */

    public function newTaskByDay($day, $month, $year, Request $request, EmailGenerator $message): Response
    {

        $dateTime = new DateTime($year . '-' . $month . '-' . $day);

        $task = new Task();
        $task->setStarthour($dateTime);
        $task->setEndhour($dateTime);

        $form = $this->createForm(TaskType::class, $task, ['user' => $this->getUser()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            foreach ($task->getUserTasks()->getValues() as $value) {
                $message->sendEmail('test sujet', $this->render('emails/NewTask.html.twig', ['task' => $task, 'usertask' => $value]), $value->getUser()->getEmail());

            }

            return $this->redirectToRoute('user_index');
        }
        return $this->render('home/day.html.twig', [

            'day' => $day,
            'month' => $month,
            'year' => $year,
            'task' => $task,
            'date' => $dateTime,
            'form' => $form->createView()


        ]);
    }


    public function taskByDay($day, $month, $year, UserTaskRepository $UserTaskRepository): Response
    {

        $dateTime = new DateTime($year . '-' . $month . '-' . $day);

        $connectedUser = $this->getUser();

        return $this->render('front/allTasks.html.twig', [

            'day' => $day,
            'month' => $month,
            'year' => $year,
            'tasks' => $UserTaskRepository->findBy(['user' => $connectedUser]),
            'date' => $dateTime,


        ]);
    }


    /**
     * @param Task $task
     * @param UserTaskRepository $UserTaskRepository
     * @return Response
     */
    public function show(Task $task, UserTaskRepository $UserTaskRepository): Response
    {
        $connectedUser = $this->getUser();

        return $this->render('front/show.html.twig', [
            'task' => $task,
            'selectprime' => $UserTaskRepository->findBy(['user' => $connectedUser])

        ]);
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return Response
     */
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [
                'id' => $task->getId(),
            ]);
        }

        return $this->render('front/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return Response
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
