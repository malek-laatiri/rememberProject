<?php

namespace App\Controller;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Entity\UserTask;
use App\Entity\Remainder;
use App\Form\UserTaskType;
use App\Form\RemainderType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/task")
 * Class TaskController
 * @package App\Controller
 */
class TaskController extends AbstractController
{


    /**
     * @Route("/", name="task_index", methods={"GET"})
     * @param TaskRepository $taskRepository
     * @return Response
     */
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @param Task $task
     * @param User $user
     * @param bool $creator
     * @param bool $approved
     * @return UserTask
     */
    public function InsertUserTask(Task $task, User $user, Bool $creator, Bool $approved)
    {
        $UserTask = new UserTask();//task creator
        $UserTask->setTask($task);
        $UserTask->setIsCreator($creator);
        $UserTask->setUser($user);
        $UserTask->setIsApproved($approved);

        return $UserTask;

    }

    /**
     * @param Task $task
     * @param datetime $RememberDate
     * @return Remainder
     */
    public function InsertRemainder(Task $task, datetime $RememberDate)
    {
        $remainder = new Remainder();
        $remainder->setTask($task);
        $remainder->setRememberDate($RememberDate);
        return $remainder;
    }

    /**
     * @param string $email
     * @param UserTask $userTask
     */
    public function InsertNewUser(string $email, UserTask $userTask)
    {
        $user = new User();
        $user->setEmail($email);
        $user->addUserTask($userTask);
        $user->setPassword(md5($email));
        $user->setUsername(trim($email, "@gmail.com"));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function newTask(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $UserTask = $this->InsertUserTask($task, $user, true, true);

            $entityManager->persist($task);
            $entityManager->flush();

            $entityManager->persist($UserTask);
            $entityManager->flush();

            foreach ($task->getRemainders() as $value) {
                $remainder = $this->InsertRemainder($task, $value->getRememberDate());

                $entityManager->persist($remainder);
            }
            $entityManager->flush();

            return $this->redirectToRoute('adminTasks');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createFormBuilder($task)
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('date', DateType::class, [
                'widget' => 'single_text',

                'attr' => ['class' => 'js-datepicker'],

            ])
            ->add('starthour', TimeType::class)
            ->add('endhour', TimeType::class)
            ->add('remainders', CollectionType::class, [
                'entry_type' => RemainderType::class,
                'prototype' => true,
                'allow_add' => true,

            ])
            ->add('userTasks', CollectionType::class, [
                'entry_type' => UserTaskType::class,
                'prototype' => true,
                'allow_add' => true,

            ])
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adminTasks', [
                'id' => $task->getId(),
            ]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adminTasks');
    }
}
