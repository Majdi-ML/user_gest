<?php

namespace App\Controller;

use App\Entity\FonctionBureau;
use App\Form\FonctionBureauType;
use App\Repository\FonctionBureauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fonctionbureau')]
class FonctionBureauController extends AbstractController
{
    #[Route('/', name: 'app_fonction_bureau_index', methods: ['GET'])]
    public function index(FonctionBureauRepository $fonctionBureauRepository): Response
    {
        return $this->render('fonction_bureau/index.html.twig', [
            'fonction_bureaus' => $fonctionBureauRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fonction_bureau_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fonctionBureau = new FonctionBureau();
        $form = $this->createForm(FonctionBureauType::class, $fonctionBureau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fonctionBureau);
            $entityManager->flush();

            return $this->redirectToRoute('app_fonction_bureau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fonction_bureau/new.html.twig', [
            'fonction_bureau' => $fonctionBureau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fonction_bureau_show', methods: ['GET'])]
    public function show(FonctionBureau $fonctionBureau): Response
    {
        return $this->render('fonction_bureau/show.html.twig', [
            'fonction_bureau' => $fonctionBureau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fonction_bureau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FonctionBureau $fonctionBureau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FonctionBureauType::class, $fonctionBureau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fonction_bureau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fonction_bureau/edit.html.twig', [
            'fonction_bureau' => $fonctionBureau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fonction_bureau_delete', methods: ['POST'])]
    public function delete(Request $request, FonctionBureau $fonctionBureau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fonctionBureau->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fonctionBureau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fonction_bureau_index', [], Response::HTTP_SEE_OTHER);
    }
}
