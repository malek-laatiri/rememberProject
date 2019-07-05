<?php

namespace App\Front\Controller;

use App\Common\Entity\Task;
use App\Front\Form\TaskType;
use App\Common\Repository\UserTaskRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class TaskController
 * @package App\Front\Controller
 */
class TaskController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function newTaskFront(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task,['user'=>$this->getUser()]);

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

    public function newTaskByDay($day, $month, $year, Request $request): Response
    {

        $dateTime = new DateTime($year . '-' . $month . '-' . $day);

        $task = new Task();
        $task->setStarthour($dateTime);
        $task->setEndhour($dateTime);

        $form = $this->createForm(TaskType::class, $task,['user'=>$this->getUser()]);
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
        $form = $this->createForm(TaskType::class, $task,['user'=>$this->getUser()]);

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
