<?php

namespace App\Controller;

use App\Entity\StatusBureau;
use App\Form\StatusBureauType;
use App\Repository\StatusBureauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/statusbureau')]
class StatusBureauController extends AbstractController
{
    #[Route('/', name: 'app_status_bureau_index', methods: ['GET'])]
    public function index(StatusBureauRepository $statusBureauRepository): Response
    {
        return $this->render('status_bureau/index.html.twig', [
            'status_bureaus' => $statusBureauRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_status_bureau_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statusBureau = new StatusBureau();
        $form = $this->createForm(StatusBureauType::class, $statusBureau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statusBureau);
            $entityManager->flush();

            return $this->redirectToRoute('app_status_bureau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('status_bureau/new.html.twig', [
            'status_bureau' => $statusBureau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_status_bureau_show', methods: ['GET'])]
    public function show(StatusBureau $statusBureau): Response
    {
        return $this->render('status_bureau/show.html.twig', [
            'status_bureau' => $statusBureau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_status_bureau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StatusBureau $statusBureau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatusBureauType::class, $statusBureau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_status_bureau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('status_bureau/edit.html.twig', [
            'status_bureau' => $statusBureau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_status_bureau_delete', methods: ['POST'])]
    public function delete(Request $request, StatusBureau $statusBureau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statusBureau->getId(), $request->request->get('_token'))) {
            $entityManager->remove($statusBureau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_status_bureau_index', [], Response::HTTP_SEE_OTHER);
    }
}
