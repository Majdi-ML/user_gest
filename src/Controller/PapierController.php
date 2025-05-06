<?php

namespace App\Controller;

use App\Entity\Papier;
use App\Form\PapierType;
use App\Repository\PapierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/papier')]
class PapierController extends AbstractController
{
    #[Route('/', name: 'app_papier_index', methods: ['GET'])]
    public function index(PapierRepository $papierRepository): Response
    {
        return $this->render('papier/index.html.twig', [
            'papiers' => $papierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_papier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $papier = new Papier();
        $form = $this->createForm(PapierType::class, $papier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($papier);
            $entityManager->flush();

            return $this->redirectToRoute('app_papier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('papier/new.html.twig', [
            'papier' => $papier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_papier_show', methods: ['GET'])]
    public function show(Papier $papier): Response
    {
        return $this->render('papier/show.html.twig', [
            'papier' => $papier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_papier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Papier $papier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PapierType::class, $papier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_papier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('papier/edit.html.twig', [
            'papier' => $papier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_papier_delete', methods: ['POST'])]
    public function delete(Request $request, Papier $papier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$papier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($papier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_papier_index', [], Response::HTTP_SEE_OTHER);
    }
}
