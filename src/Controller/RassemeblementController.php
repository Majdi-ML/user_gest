<?php

namespace App\Controller;

use App\Entity\Rassemeblement;
use App\Form\RassemeblementType;
use App\Repository\RassemeblementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rassemeblement')]
class RassemeblementController extends AbstractController
{
    #[Route('/', name: 'app_rassemeblement_index', methods: ['GET'])]
    public function index(RassemeblementRepository $rassemeblementRepository): Response
    {
        return $this->render('rassemeblement/index.html.twig', [
            'rassemeblements' => $rassemeblementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rassemeblement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rassemeblement = new Rassemeblement();
        $form = $this->createForm(RassemeblementType::class, $rassemeblement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rassemeblement);
            $entityManager->flush();

            return $this->redirectToRoute('app_rassemeblement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rassemeblement/new.html.twig', [
            'rassemeblement' => $rassemeblement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rassemeblement_show', methods: ['GET'])]
    public function show(Rassemeblement $rassemeblement): Response
    {
        return $this->render('rassemeblement/show.html.twig', [
            'rassemeblement' => $rassemeblement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rassemeblement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rassemeblement $rassemeblement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RassemeblementType::class, $rassemeblement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rassemeblement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rassemeblement/edit.html.twig', [
            'rassemeblement' => $rassemeblement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rassemeblement_delete', methods: ['POST'])]
    public function delete(Request $request, Rassemeblement $rassemeblement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rassemeblement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rassemeblement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rassemeblement_index', [], Response::HTTP_SEE_OTHER);
    }
}
