<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    #[Route('/dashboard/users',name: 'dashboard_tables', methods: ['GET'])]
    public function users(UtilisateurRepository $userRepository): Response
    {
        return $this->render('dashboard/home/tables.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
