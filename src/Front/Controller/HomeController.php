<?php

namespace App\Front\Controller;


use App\Common\Repository\TaskRepository;
use App\Common\Repository\UserRepository;
use App\Common\Repository\UserTaskRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    /**
     * @param $first_day
     * @param UserTaskRepository $UserTaskRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function home($first_day, UserTaskRepository $UserTaskRepository, UserRepository $userRepository): Response
    {

        $connectedUser = $this->getUser();
        $role = "ROLE_SUPER_ADMIN";


        $adminTasks = $userRepository->findByRole($role);
        return $this->render('home/index.html.twig', [

            'first_day' => $first_day,
            'selectprime' => $UserTaskRepository->findBy(['user' => $connectedUser]),
            'adminTasks' => $UserTaskRepository->findBy(['user' => $adminTasks])


        ]);
    }

    /**
     * @param TaskRepository $task
     * @param UserTaskRepository $UserTaskRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(TaskRepository $task, UserTaskRepository $UserTaskRepository, UserRepository $userRepository): Response
    {
        $connectedUser = $this->getUser();
        $role = "ROLE_SUPER_ADMIN";
        $firstDay = time();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',

            'first_day' => $firstDay,
            'select' => $task->findAll(),
            'selectprime' => $UserTaskRepository->findBy(['user' => $connectedUser]),
            'adminTasks' => $userRepository->findBy(['roles' => $role])


        ]);
    }


}
