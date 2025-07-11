<?php

namespace App\Controller;

use App\Entity\DossierSinistre;
use App\Entity\Reclamation;
use App\Form\DossierSinistreForm;
use App\Form\ReclamationForm;
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('user/dossier_sinistre/index.html.twig', [
            'dossier_sinistres' => $dossierSinistreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dossier_sinistre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') &&!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
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
    public function show(DossierSinistre $dossierSinistre,Request $request,
                         EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $reclamation = new Reclamation();
        $reclamation->setDossierSinistre($dossierSinistre);
        $form = $this->createForm(ReclamationForm::class, $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            $this->addFlash('success', 'Reclamation added successfully!');
            return $this->redirectToRoute('app_dossier_sinistre_show', ['id' => $dossierSinistre->getId()]);
        }
        return $this->render('user/dossier_sinistre/show.html.twig', [
            'dossier_sinistre' => $dossierSinistre,
            'reclamation_form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dossier_sinistre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DossierSinistre $dossierSinistre, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') &&!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
        if ($this->isCsrfTokenValid('delete'.$dossierSinistre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dossierSinistre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dossier_sinistre_index', [], Response::HTTP_SEE_OTHER);
    }

    /////////////////////////////////////////////////DASHBOARD
    ///


}
