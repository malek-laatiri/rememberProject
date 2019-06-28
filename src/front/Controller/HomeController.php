<?php

namespace App\front\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{

    public function home($first_day): Response
    {

        dump($first_day);


        return $this->render('home/index.html.twig', [

            'first_day' => $first_day


        ]);
    }


    public function day($day, $month, $year): Response
    {

        return $this->render('home/day.html.twig', [

            'day' => $day,
            'month' => $month,
            'year' => $year


        ]);
    }


    public function index(): Response
    {
        $connecteduser=$this->getUser()->getUsername();
        $roleuser=$this->getUser()->getRoles();

        dump($roleuser);
        $first_day = time();
        dump($first_day);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',

            'first_day' => $first_day


        ]);
    }



}
