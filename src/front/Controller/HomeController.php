<?php

namespace App\front\Controller;


use App\front\Repository\TaskRepository;
use App\front\Repository\UserTaskRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{

    public function home($first_day,UserTaskRepository $UserTaskRepository): Response
    {

        dump($first_day);
        $connecteduser=$this->getUser();


        return $this->render('home/index.html.twig', [

            'first_day' => $first_day,
            'selectprime'=>$UserTaskRepository->findBy(['user'=>$connecteduser])


        ]);
    }




    public function index(TaskRepository $task,UserTaskRepository $UserTaskRepository): Response
    {
        $connecteduser=$this->getUser();

        $first_day = time();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',

            'first_day' => $first_day,
               'select'=>$task->findAll(),
            'selectprime'=>$UserTaskRepository->findBy(['user'=>$connecteduser])

        ]);
    }



}