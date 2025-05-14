<?php

namespace App\Controller;

use App\Entity\DossierSinistre;
use App\Form\DossierSinistreForm;
use App\Repository\DossierSinistreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dossier/sinistre')]
final class DossierSinistreController extends AbstractController
{
    #[Route(name: 'app_dossier_sinistre_index', methods: ['GET'])]
    public function index(DossierSinistreRepository $dossierSinistreRepository): Response
    {
        return $this->render('user/dossier_sinistre/index.html.twig', [
            'dossier_sinistres' => $dossierSinistreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dossier_sinistre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dossierSinistre = new DossierSinistre();
        $form = $this->createForm(DossierSinistreForm::class, $dossierSinistre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dossierSinistre);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_sinistre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/dossier_sinistre/new.html.twig', [
            'dossier_sinistre' => $dossierSinistre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dossier_sinistre_show', methods: ['GET'])]
    public function show(DossierSinistre $dossierSinistre): Response
    {
        return $this->render('user/dossier_sinistre/show.html.twig', [
            'dossier_sinistre' => $dossierSinistre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dossier_sinistre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DossierSinistre $dossierSinistre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DossierSinistreForm::class, $dossierSinistre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_sinistre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/dossier_sinistre/edit.html.twig', [
            'dossier_sinistre' => $dossierSinistre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dossier_sinistre_delete', methods: ['POST'])]
    public function delete(Request $request, DossierSinistre $dossierSinistre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dossierSinistre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dossierSinistre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dossier_sinistre_index', [], Response::HTTP_SEE_OTHER);
    }

    /////////////////////////////////////////////////DASHBOARD
    ///
    #[Route('/dashboard',name: 'app_dossier_sinistre_index_dashboard', methods: ['GET'])]
    public function adminIndex(DossierSinistreRepository $dossierSinistreRepository): Response
    {
        return $this->render('dashboard/dossier_sinistre/index.html.twig', [
            'dossier_sinistres' => $dossierSinistreRepository->findAll(),
        ]);
    }

    #[Route('/dashboard/new', name: 'app_dossier_sinistre_new_dashboard', methods: ['GET', 'POST'])]
    public function adminNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dossierSinistre = new DossierSinistre();
        $form = $this->createForm(DossierSinistreForm::class, $dossierSinistre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dossierSinistre);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_sinistre_index_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/dossier_sinistre/new.html.twig', [
            'dossier_sinistre' => $dossierSinistre,
            'form' => $form,
        ]);
    }

    #[Route('/dashboard/{id}', name: 'app_dossier_sinistre_show_dashboard', methods: ['GET'])]
    public function adminShow(DossierSinistre $dossierSinistre): Response
    {
        return $this->render('dashboard/dossier_sinistre/show.html.twig', [
            'dossier_sinistre' => $dossierSinistre,
        ]);
    }

    #[Route('/dashboard/{id}/edit', name: 'app_dossier_sinistre_edit_dashboard', methods: ['GET', 'POST'])]
    public function adminEdit(Request $request, DossierSinistre $dossierSinistre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DossierSinistreForm::class, $dossierSinistre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_sinistre_index_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/dossier_sinistre/edit.html.twig', [
            'dossier_sinistre' => $dossierSinistre,
            'form' => $form,
        ]);
    }

    #[Route('/dashboard/{id}', name: 'app_dossier_sinistre_delete_dashboard', methods: ['POST'])]
    public function adminDelete(Request $request, DossierSinistre $dossierSinistre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dossierSinistre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dossierSinistre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dossier_sinistre_index_dashboard', [], Response::HTTP_SEE_OTHER);
    }

}
