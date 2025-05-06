<?php

namespace App\Controller;

use App\Entity\NaturePaiement;
use App\Form\NaturePaiementType;
use App\Repository\NaturePaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/naturepaiement')]
class NaturePaiementController extends AbstractController
{
    #[Route('/', name: 'app_nature_paiement_index', methods: ['GET'])]
    public function index(NaturePaiementRepository $naturePaiementRepository): Response
    {
        return $this->render('nature_paiement/index.html.twig', [
            'nature_paiements' => $naturePaiementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nature_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $naturePaiement = new NaturePaiement();
        $form = $this->createForm(NaturePaiementType::class, $naturePaiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($naturePaiement);
            $entityManager->flush();

            return $this->redirectToRoute('app_nature_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nature_paiement/new.html.twig', [
            'nature_paiement' => $naturePaiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nature_paiement_show', methods: ['GET'])]
    public function show(NaturePaiement $naturePaiement): Response
    {
        return $this->render('nature_paiement/show.html.twig', [
            'nature_paiement' => $naturePaiement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nature_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NaturePaiement $naturePaiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NaturePaiementType::class, $naturePaiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nature_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nature_paiement/edit.html.twig', [
            'nature_paiement' => $naturePaiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nature_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, NaturePaiement $naturePaiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$naturePaiement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($naturePaiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nature_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
