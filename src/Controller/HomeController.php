<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home/{first_day}", name="home")
     */
    public function home($first_day): Response
    {

        dump($first_day);


        return $this->render('home/index.html.twig', [

            'first_day' => $first_day


        ]);
    }

    /**
     * @Route("/day/{day}/{month}/{year}", name="day")
     */
    public function day($day, $month, $year): Response
    {

        return $this->render('home/day.html.twig', [

            'day' => $day,
            'month' => $month,
            'year' => $year


        ]);
    }


    /**
     * @Route("/", name="index")
     */
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
