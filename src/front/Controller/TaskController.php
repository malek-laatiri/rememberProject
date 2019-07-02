<?php

namespace App\front\Controller;

use App\Backoffice\Repository\TaskRepository;
use App\common\Entity\Task;
use App\front\Form\TaskType;
use App\front\Repository\UserTaskRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class TaskController
 * @package App\front\Controller
 */
class TaskController extends AbstractController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function newTaskFront(Request $request, TaskRepository $taskRepository): Response
    {
        $task = new Task();
        $user = $this->getUser();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('user_index');
        }

        return $this->render('front/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @param $day
     * @param $month
     * @param $year
     * @param Request $request
     * @return Response
     * @throws \Exception
     */

    public function NewTaskByDay($day, $month, $year, Request $request): Response
    {
        $time = strtotime($month . '/' . $day . '/' . $year);

        $dateTime = new DateTime($year.'-'.$month.'-'.$day);

        $task = new Task();
        $task->setStarthour($dateTime);
        $user = $this->getUser();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('user_index');
        }
        return $this->render('home/day.html.twig', [

            'day' => $day,
            'month' => $month,
            'year' => $year,
            'task' => $task,
            'form' => $form->createView()


        ]);
    }


    /**
     * @param Task $task
     * @return Response
     */

    public function show(Task $task, UserTaskRepository $UserTaskRepository): Response
    {
        $connecteduser = $this->getUser();

        return $this->render('front/show.html.twig', [
            'task' => $task,
            'selectprime' => $UserTaskRepository->findBy(['user' => $connecteduser])

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

        return $this->render('task/edit.html.twig', [
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
