<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationForm;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reclamation/dashboard')]
final class ReclamationDashboardController extends AbstractController
{
    #[Route('/index',name: 'app_reclamation_index_dashboard', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
        return $this->render('dashboard/reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationForm::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            $this->addFlash('success', 'Reclamation created successfully');
            return $this->redirectToRoute('app_reclamation_index_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/reclamation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_reclamation_show', methods: ['GET'])]
    public function show(int $id, ReclamationRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
        $reclamation = $repository->find($id);
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        return $this->render('dashboard/reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, ReclamationRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
        $reclamation = $repository->find($id);
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        $form = $this->createForm(ReclamationForm::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Reclamation updated successfully');
            return $this->redirectToRoute('app_reclamation_index_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, ReclamationRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Then check for either ROLE_ADMIN OR ROLE_SUPER_ADMIN
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('You need admin privileges to access this page');
        }
        $reclamation = $repository->find($id);
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
            $this->addFlash('success', 'Reclamation deleted successfully');
        }

        return $this->redirectToRoute('app_reclamation_index_dashboard', [], Response::HTTP_SEE_OTHER);
    }
}