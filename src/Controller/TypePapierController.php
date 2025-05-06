<?php

namespace App\Controller;

use App\Entity\TypePapier;
use App\Form\TypePapierType;
use App\Repository\TypePapierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/typepapier')]
class TypePapierController extends AbstractController
{
    #[Route('/', name: 'app_type_papier_index', methods: ['GET'])]
    public function index(TypePapierRepository $typePapierRepository): Response
    {
        return $this->render('type_papier/index.html.twig', [
            'type_papiers' => $typePapierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_papier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typePapier = new TypePapier();
        $form = $this->createForm(TypePapierType::class, $typePapier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typePapier);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_papier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_papier/new.html.twig', [
            'type_papier' => $typePapier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_papier_show', methods: ['GET'])]
    public function show(TypePapier $typePapier): Response
    {
        return $this->render('type_papier/show.html.twig', [
            'type_papier' => $typePapier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_papier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypePapier $typePapier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypePapierType::class, $typePapier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_papier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_papier/edit.html.twig', [
            'type_papier' => $typePapier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_papier_delete', methods: ['POST'])]
    public function delete(Request $request, TypePapier $typePapier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typePapier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typePapier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_papier_index', [], Response::HTTP_SEE_OTHER);
    }
}
