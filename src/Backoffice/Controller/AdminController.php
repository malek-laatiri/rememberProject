<?php

namespace App\Backoffice\Controller;

use App\Common\Repository\TaskRepository;
use App\Common\Repository\UserRepository;
use App\Common\Repository\UserTaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends AbstractController
{
    /**
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function admin(UserRepository $repository)
    {


        $users = $repository->findAll();
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users
        ]);
    }

    /**
     * @param TaskRepository $repositoryT
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function adminTasks(UserTaskRepository $UserTaskRepository)
    {
        $connectedUser=$this->getUser();

        return $this->render('admin/tasks.html.twig', [
            'controller_name' => 'AdminController',
            'tasks' => $UserTaskRepository->findBy(['user'=>$connectedUser])
        ]);
    }
}
