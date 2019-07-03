<?php

namespace App\Backoffice\Controller;

use App\Common\Entity\Task;
use App\Common\Entity\User;
use App\Backoffice\Form\TaskType;
use App\Common\Entity\UserTask;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * Class TaskController
 * @package App\Controller
 */
class TaskController extends AbstractController
{
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
     * @param Request $request
     * @return Response
     */
    public function newTask(Request $request): Response
    {
        $task = new Task();
        $user = $this->getUser();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $userTask = $this->InsertUserTask($task, $user, true, true);
           $entityManager->persist($userTask);
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('admin_tasks');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
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

            return $this->redirectToRoute('admin_tasks', [
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

        return $this->redirectToRoute('admin_tasks');
    }
}
