<?php

namespace App\Controller;

use App\Entity\FraisSyndic;
use App\Form\FraisSyndicType;
use App\Repository\FraisSyndicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/frais/syndic')]
class FraisSyndicController extends AbstractController
{
    #[Route('/', name: 'app_frais_syndic_index', methods: ['GET'])]
    public function index(FraisSyndicRepository $fraisSyndicRepository): Response
    {
        return $this->render('frais_syndic/index.html.twig', [
            'frais_syndics' => $fraisSyndicRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_frais_syndic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fraisSyndic = new FraisSyndic();
        $form = $this->createForm(FraisSyndicType::class, $fraisSyndic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fraisSyndic);
            $entityManager->flush();

            return $this->redirectToRoute('app_frais_syndic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frais_syndic/new.html.twig', [
            'frais_syndic' => $fraisSyndic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_frais_syndic_show', methods: ['GET'])]
    public function show(FraisSyndic $fraisSyndic): Response
    {
        return $this->render('frais_syndic/show.html.twig', [
            'frais_syndic' => $fraisSyndic,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_frais_syndic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FraisSyndic $fraisSyndic, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FraisSyndicType::class, $fraisSyndic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_frais_syndic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frais_syndic/edit.html.twig', [
            'frais_syndic' => $fraisSyndic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_frais_syndic_delete', methods: ['POST'])]
    public function delete(Request $request, FraisSyndic $fraisSyndic, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fraisSyndic->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fraisSyndic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_frais_syndic_index', [], Response::HTTP_SEE_OTHER);
    }
}
