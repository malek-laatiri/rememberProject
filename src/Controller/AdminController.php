<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends AbstractController
{

    public function admin(UserRepository $repository)
    {
        $users = $repository->findAll();
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users
        ]);
    }


    public function adminTasks(TaskRepository $repositoryT)
    {
        $tasks = $repositoryT->findAll();
        return $this->render('admin/tasks.html.twig', [
            'controller_name' => 'AdminController',
            'tasks' => $tasks
        ]);
    }
}
