<?php

namespace App\front\Controller;

use App\common\Entity\Task;
use App\common\Entity\User;
use App\front\Form\TaskType;
use App\common\Entity\UserTask;
use App\front\Form\UserTaskType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class TaskController
 * @package App\front\Controller
 */
class TaskController extends AbstractController
{

    public function InsertUserTask(Task $task, User $user, Bool $creator)
    {
        $UserTask = new UserTask();//task creator
        $UserTask->setTask($task);
        $UserTask->setIsCreator($creator);
        $UserTask->setUser($user);
        return $UserTask;
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function newTaskFront(Request $request): Response
    {
        $task = new Task();
        $user = $this->getUser();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $userTask = $this->InsertUserTask($task, $user, false);
            $userTask->setIsApproved(true);
            foreach ($userTask->getUser() as $value)
            {
                $newUser=$this->InsertUserTask($task,$value,false);
                $entityManager->persist($newUser);


            }
            $entityManager->persist($userTask);

            $entityManager->persist($task);

            $entityManager->flush();
            return $this->redirectToRoute('user_index');
        }

        return $this->render('front/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }





    public function day($day, $month, $year,Request $request): Response
    {
        $time = strtotime($month.'/'.$day.'/'.$year);

        $newformat = date('m/d/Y',$time);


        $task = new Task();
        $user = $this->getUser();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $userTask = $this->InsertUserTask($task, $user, true);
            $userTask->setIsApproved(true);
            foreach ($userTask->getUser() as $value)
            {
                $newUser=$this->InsertUserTask($task,$value,false);
                $entityManager->persist($newUser);


            }
            $entityManager->persist($userTask);

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

    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
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

        return $this->redirectToRoute('adminTasks');
    }
}
