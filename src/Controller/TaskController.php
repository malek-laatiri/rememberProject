<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\UserTask;
use App\Entity\Remainder;
use App\Form\UserTaskType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/task")
 *
 * Class TaskController
 * @package App\Controller
 */
class TaskController extends AbstractController
{

    /**
     * @Route("/", name="task_index", methods={"GET"})
     *
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
     * @Route("/new", name="task_new", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        $UserTask = new UserTask();//task creator

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();
            $entityManager->persist($task);


            $UserTask->setTask($task);
            $UserTask->setIsCreator(true);
            $UserTask->setUser($user);
            $UserTask->setIsApproved(true);


            $entityManager->flush();
            $entityManager->persist($task);

            $entityManager->flush();
            $entityManager->persist($UserTask);

            foreach ($task->getRemainders() as $value) {
                $remainder = new Remainder();
                $remainder->setTask($task);
                $remainder->setRememberDate($value->getRememberDate());
                $entityManager->persist($remainder);
                $entityManager->flush();
            }



            foreach ($task->getUserTasks() as $value) {
                $user = new UserTask();
                $user->setTask($task);
                $user->setIsCreator(true);

                $user->setUser($value);
                $entityManager->persist($user);
                $entityManager->flush();
            }




            return $this->redirectToRoute('task_index');
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
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_index', [
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

        return $this->redirectToRoute('task_index');
    }
}
