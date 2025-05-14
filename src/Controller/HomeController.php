<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('user/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/dashboard', name: 'app_home_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('dashboard/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
