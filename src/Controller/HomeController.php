<?php

namespace App\Controller;

use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())|| in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles()))
        {
            return $this->render('dashboard/home/index.html.twig', [
                'controller_name' => 'HomeController',
            ]);
        }
        return $this->render('user/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/dashboard', name: 'app_home_dashboard')]
    public function dashboard(ReclamationRepository $reclamationRepository): Response
    {
        $stats = [
            'total' => $reclamationRepository->count([]),
            'pending' => $reclamationRepository->count(['statut' => 'Pending']),
            'treated' => $reclamationRepository->count(['statut' => 'Treated']),

        ];
        return $this->render('dashboard/home/index.html.twig', [

            'controller_name' => 'HomeController',
            'reclamationStats' => $stats,
        ]);
    }
}
