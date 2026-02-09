<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/recette')]
class RecetteController extends AbstractController
{
    #[Route('/', name: 'app_recette_index')]
    public function index(RecetteRepository $recetteRepository): Response
    {
        $recettes = $recetteRepository->findAll();

        // Group recettes by year and month
        $recettesByYear = [];
        foreach ($recettes as $recette) {
            $date = $recette->getDateRecette();
            if ($date) {
                $year = $date->format('Y');
                $month = $date->format('F');

                if (!isset($recettesByYear[$year])) {
                    $recettesByYear[$year] = [];
                }

                if (!isset($recettesByYear[$year][$month])) {
                    $recettesByYear[$year][$month] = [
                        'Cotisation Syndic' => ['Espece' => 0, 'Bancaire' => 0],
                        'Autre revenu' => ['Espece' => 0, 'Bancaire' => 0],
                    ];
                }

                $type = $recette->getTypeRecette();
                $nature = $recette->getNatureRecette();
                $montant = $recette->getMontant();
                
                if (isset($recettesByYear[$year][$month][$type][$nature])) {
                    $recettesByYear[$year][$month][$type][$nature] += $montant;
                }
            }
        }

        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
            'recettesByYear' => $recettesByYear,
        ]);
    }

    #[Route('/new', name: 'app_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        $user = $security->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $recette->setUser($user);
            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_show', methods: ['GET'])]
    public function show(Recette $recette): Response
    {
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    }
}
