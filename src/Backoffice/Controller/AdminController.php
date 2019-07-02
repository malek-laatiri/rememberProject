<?php

namespace App\Backoffice\Controller;

use App\Backoffice\Repository\TaskRepository;
use App\Backoffice\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

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
    public function adminTasks(TaskRepository $repositoryT)
    {
        $tasks = $repositoryT->findAll();
        return $this->render('admin/tasks.html.twig', [
            'controller_name' => 'AdminController',
            'tasks' => $tasks
        ]);
    }
}
