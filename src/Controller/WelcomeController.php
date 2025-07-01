<?php

namespace App\Controller;

use App\Repository\DossierSinistreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WelcomeController extends AbstractController
{
    #[Route('/', name: 'app_welcome')]
    public function index(Request $request, DossierSinistreRepository $dossierRepository): Response
    {
        $searchId = $request->query->get('search');
        $dossier = null;

        if ($searchId) {
            $dossier = $dossierRepository->findOneBy(['numeroPolice' => $searchId]);
        }

        return $this->render('welcome/index.html.twig', [
            'dossier' => $dossier,
            'searchId' => $searchId
        ]);
    }
}
