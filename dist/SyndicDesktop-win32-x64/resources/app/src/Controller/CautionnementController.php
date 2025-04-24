<?php

namespace App\Controller;

use App\Entity\Cautionnement;
use App\Form\CautionnementType;
use App\Repository\CautionnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cautionnement')]
class CautionnementController extends AbstractController
{
    #[Route('/', name: 'app_cautionnement_index', methods: ['GET'])]
    public function index(CautionnementRepository $cautionnementRepository): Response
    {
        return $this->render('cautionnement/index.html.twig', [
            'cautionnements' => $cautionnementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cautionnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cautionnement = new Cautionnement();
        $form = $this->createForm(CautionnementType::class, $cautionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cautionnement);
            $entityManager->flush();

            return $this->redirectToRoute('app_cautionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cautionnement/new.html.twig', [
            'cautionnement' => $cautionnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cautionnement_show', methods: ['GET'])]
    public function show(Cautionnement $cautionnement): Response
    {
        return $this->render('cautionnement/show.html.twig', [
            'cautionnement' => $cautionnement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cautionnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cautionnement $cautionnement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CautionnementType::class, $cautionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cautionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cautionnement/edit.html.twig', [
            'cautionnement' => $cautionnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cautionnement_delete', methods: ['POST'])]
    public function delete(Request $request, Cautionnement $cautionnement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cautionnement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cautionnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cautionnement_index', [], Response::HTTP_SEE_OTHER);
    }
}
