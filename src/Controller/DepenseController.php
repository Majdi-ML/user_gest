<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Form\DepenseType;
use App\Repository\DepenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
#[Route('/depense')]
class DepenseController extends AbstractController
{
   #[Route('/', name: 'app_depense_index')]
    public function index(DepenseRepository $depenseRepository): Response
    {
        $depenses = $depenseRepository->findAll();

        // Group expenses by year and month
        $depensesByYear = [];
        foreach ($depenses as $depense) {
            $date = $depense->getDateDepense(); // Adjust this method based on your Depense entity
            if ($date) {
                $year = $date->format('Y');
                $month = $date->format('F'); // Full month name (e.g., "January")

                if (!isset($depensesByYear[$year])) {
                    $depensesByYear[$year] = [];
                }

                if (!isset($depensesByYear[$year][$month])) {
                    $depensesByYear[$year][$month] = [
                        'SALAIRE' => 0,
                        'CNSS' => 0,
                        'STEG' => 0,
                        'SONEDE' => 0,
                        'IMPOTS' => 0,
                        'JARDINAGE' => 0,
                        'PRODUITS_ENTRETIEN' => 0,
                        'GROS_ŒUVRES_ENTRETIEN' => 0,
                        'FRAIS_JURIDIQUE' => 0,
                        'DIVERS' => 0,
                    ];
                }

                // Map the expense type to the corresponding category and add the amount
                $type = $depense->getType(); // Adjust this method based on your Depense entity
                $montant = $depense->getMontant(); // Adjust this method based on your Depense entity
                $depensesByYear[$year][$month][$type] += $montant;
            }
        }

        return $this->render('depense/index.html.twig', [
            'depenses' => $depenses,
            'depensesByYear' => $depensesByYear,
        ]);
    }


    #[Route('/new', name: 'app_depense_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
{
    $depense = new Depense();
    $form = $this->createForm(DepenseType::class, $depense);
    $form->handleRequest($request);
    $user = $security->getUser();

    if ($form->isSubmitted() && $form->isValid()) {
        $depense->setUser($user);
        $entityManager->persist($depense);
        $entityManager->flush();

        return $this->redirectToRoute('app_depense_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('depense/new.html.twig', [
        'depense' => $depense,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_depense_show', methods: ['GET'])]
    public function show(Depense $depense): Response
    {
        return $this->render('depense/show.html.twig', [
            'depense' => $depense,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_depense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Depense $depense, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_depense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('depense/edit.html.twig', [
            'depense' => $depense,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_depense_delete', methods: ['POST'])]
    public function delete(Request $request, Depense $depense, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$depense->getId(), $request->request->get('_token'))) {
            $entityManager->remove($depense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_depense_index', [], Response::HTTP_SEE_OTHER);
    }
}
