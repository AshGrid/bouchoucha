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

#[Route('/dossier/sinistre/dashboard')]
final class DossierSinistreDashboardController extends AbstractController
{
    #[Route( name: 'app_dossier_sinistre_dashboard', methods: ['GET'])]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
        return $this->render('dossier_sinistre_dashboard/index.html.twig', [
            'controller_name' => 'DossierSinistreDashboardController',
        ]);
    }

    #[Route('/dashboard',name: 'app_dossier_sinistre_index_dashboard', methods: ['GET'])]
    public function adminIndex(DossierSinistreRepository $dossierSinistreRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
        return $this->render('dashboard/dossier_sinistre/index.html.twig', [
            'dossier_sinistres' => $dossierSinistreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dossier_sinistre_new_dashboard', methods: ['GET', 'POST'])]
    public function adminNew(
        Request $request,
        EntityManagerInterface $entityManager,
        DossierSinistreRepository $dossierRepo
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }

        $dossierSinistre = new DossierSinistre();
        $form = $this->createForm(DossierSinistreForm::class, $dossierSinistre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generate dossier ID
            $nextId = $dossierRepo->getNextYearlyId($entityManager);
            $currentYear = date('y');
            $idDossier = sprintf('S%s%04d', $currentYear, $nextId);
            $dossierSinistre->setIdDossier($idDossier);

            // Handle file uploads
            $uploadedFiles = $form->get('carImages')->getData();
            $carImageNames = [];

            if ($uploadedFiles) {
                foreach ($uploadedFiles as $uploadedFile) {
                    $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
                    $uploadedFile->move(
                        $this->getParameter('car_images_directory'),
                        $newFilename
                    );
                    $carImageNames[] = $newFilename;
                }
                $dossierSinistre->setCarImageNames($carImageNames);
            }

            $entityManager->persist($dossierSinistre);
            $entityManager->flush();

            $this->addFlash('success', 'Dossier created successfully!');
            return $this->redirectToRoute('app_dossier_sinistre_index_dashboard');
        }

        return $this->render('dashboard/dossier_sinistre/new.html.twig', [
            'dossier_sinistre' => $dossierSinistre,
            'form' => $form->createView(),
        ]);
    }

//    #[Route('/new', name: 'app_dossier_sinistre_new_dashboard', methods: ['GET', 'POST'])]
//    public function adminNew(Request $request, EntityManagerInterface $entityManager,DossierSinistreRepository $dossierRepo): Response
//    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
//
//        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
//        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
//            throw $this->createAccessDeniedException('You need admin privileges to access this page');
//        }
//        $dossierSinistre = new DossierSinistre();
//        $form = $this->createForm(DossierSinistreForm::class, $dossierSinistre);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $nextId = $dossierRepo->getNextYearlyId($entityManager);
//            $currentYear = date('y');
//            $idDossier = sprintf('S%s%04d', $currentYear, $nextId);
//
//            $dossierSinistre->setIdDossier($idDossier);
//
//
//            $entityManager->persist($dossierSinistre);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_dossier_sinistre_index_dashboard', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('dashboard/dossier_sinistre/new.html.twig', [
//            'dossier_sinistre' => $dossierSinistre,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'app_dossier_sinistre_show_dashboard', methods: ['GET'])]
    public function adminShow(DossierSinistre $dossierSinistre): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
        return $this->render('dashboard/dossier_sinistre/show.html.twig', [
            'dossier_sinistre' => $dossierSinistre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dossier_sinistre_edit_dashboard', methods: ['GET', 'POST'])]
    public function adminEdit(Request $request, DossierSinistre $dossierSinistre, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
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

    #[Route('/{id}', name: 'app_dossier_sinistre_delete_dashboard', methods: ['POST'])]
    public function adminDelete(Request $request, DossierSinistre $dossierSinistre, EntityManagerInterface $entityManager): Response
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

        return $this->redirectToRoute('app_dossier_sinistre_index_dashboard', [], Response::HTTP_SEE_OTHER);
    }


//    public function edit(Request $request, DossierSinistre $dossier): Response
//    {
//        $form = $this->createForm(DossierSinistreType::class, $dossier);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            $this->addFlash('success', 'Dossier updated successfully');
//            return $this->redirectToRoute('app_dossier_show', ['id' => $dossier->getId()]);
//        }
//
//        return $this->render('dossier/edit.html.twig', [
//            'dossier' => $dossier,
//            'form' => $form->createView(),
//        ]);
//    }

}
