<?php

namespace App\Controller;

use App\Entity\Caisse;
use App\Repository\RecetteRepository;
use App\Repository\DepenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        Request $request,
        RecetteRepository $recetteRepo,
        DepenseRepository $depenseRepo,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier si l'utilisateur est admin
        $isAdmin = $this->getUser() && $this->getUser()->getUserIdentifier() === 'admin@admin.com';

        // Create a new Caisse entity for the form
        $newCaisse = new Caisse();
        $newCaisse->setAnnee((int)date('Y') - 1); // Default to previous year
        $newCaisse->setSolde(0.0);

        // Create form for adding a new Caisse
        $form = $this->createFormBuilder($newCaisse)
            ->add('solde', null, [
                'label' => 'Solde de la caisse',
                'attr' => ['step' => '0.01'],
            ])
            ->add('annee', IntegerType::class, [
                'label' => 'Année du solde (année précédente)',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si une caisse existe déjà pour cette année
            $existingCaisse = $entityManager->getRepository(Caisse::class)->findOneBy(['annee' => $newCaisse->getAnnee()]);
            if ($existingCaisse) {
                $this->addFlash('error', sprintf('Une caisse existe déjà pour l\'année %s.', $newCaisse->getAnnee()));
            } else {
                $entityManager->persist($newCaisse);
                $entityManager->flush();
                $this->addFlash('success', sprintf('La caisse pour l\'année %s a été ajoutée avec succès.', $newCaisse->getAnnee()));
            }
            return $this->redirectToRoute('app_dashboard');
        }

        // Fetch all Caisse entities
        $caisses = $entityManager->getRepository(Caisse::class)->findBy([], ['annee' => 'ASC']);

        // Fetch distinct years from Recette using native SQL
        $connection = $entityManager->getConnection();
        $sql = "SELECT DISTINCT strftime('%Y', date_recette) as year FROM recette";
        $stmt = $connection->executeQuery($sql);
        $recetteYears = array_column($stmt->fetchAllAssociative(), 'year');

        // Fetch distinct years from Depense using native SQL
        $sql = "SELECT DISTINCT strftime('%Y', date_depense) as year FROM depense";
        $stmt = $connection->executeQuery($sql);
        $depenseYears = array_column($stmt->fetchAllAssociative(), 'year');

        // Combine and sort unique years
        $years = array_unique(array_merge($recetteYears, $depenseYears));
        $years = array_filter($years); // Remove empty values
        sort($years);

        // Current system date for display
        $systemDate = new \DateTime();

        $financialData = [];

        foreach ($years as $year) {
            // Find the initial balance for the year (solde of the previous year's caisse)
            $previousYear = (int)$year - 1;
            $caisseForYear = $entityManager->getRepository(Caisse::class)->findOneBy(['annee' => $previousYear]);
            $initialCaisse = $caisseForYear ? $caisseForYear->getSolde() : 0.0;
            $caisseValue = $initialCaisse;

            $yearlyData = [
                'recettes' => 0,
                'depenses' => 0,
                'months' => [],
                'initialCaisse' => $initialCaisse,
                'initialCaisseYear' => $previousYear,
            ];

            for ($i = 1; $i <= 12; $i++) {
                $monthNum = str_pad($i, 2, '0', STR_PAD_LEFT);
                $monthName = date('F', mktime(0, 0, 0, $i, 1));
                $monthNameFr = [
                    'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars',
                    'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin',
                    'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre',
                    'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
                ][$monthName];

                // Fetch Recettes Espèce
                $sql = "SELECT COALESCE(SUM(montant), 0) as total FROM recette 
                        WHERE strftime('%Y', date_recette) = :year 
                        AND strftime('%m', date_recette) = :month 
                        AND nature_recette = 'Espece'";
                $stmt = $connection->executeQuery($sql, ['year' => $year, 'month' => $monthNum]);
                $recetteEspece = (float) $stmt->fetchOne();

                // Fetch Recettes Bancaire
                $sql = "SELECT COALESCE(SUM(montant), 0) as total FROM recette 
                        WHERE strftime('%Y', date_recette) = :year 
                        AND strftime('%m', date_recette) = :month 
                        AND nature_recette = 'Bancaire'";
                $stmt = $connection->executeQuery($sql, ['year' => $year, 'month' => $monthNum]);
                $recetteBanque = (float) $stmt->fetchOne();

                // Fetch Dépenses Espèce
                $sql = "SELECT COALESCE(SUM(montant), 0) as total FROM depense 
                        WHERE strftime('%Y', date_depense) = :year 
                        AND strftime('%m', date_depense) = :month 
                        AND nature_depense = 'Espece'";
                $stmt = $connection->executeQuery($sql, ['year' => $year, 'month' => $monthNum]);
                $depenseEspece = (float) $stmt->fetchOne();

                // Fetch Dépenses Bancaire
                $sql = "SELECT COALESCE(SUM(montant), 0) as total FROM depense 
                        WHERE strftime('%Y', date_depense) = :year 
                        AND strftime('%m', date_depense) = :month 
                        AND nature_depense = 'Bancaire'";
                $stmt = $connection->executeQuery($sql, ['year' => $year, 'month' => $monthNum]);
                $depenseBanque = (float) $stmt->fetchOne();

                // Calculate totals
                $totalRecette = $recetteEspece + $recetteBanque;
                $totalDepense = $depenseEspece + $depenseBanque;

                // Update CAISSE
                $caisseValue += $totalRecette - $totalDepense;

                $yearlyData['months'][$monthNameFr] = [
                    'recette_espece' => $recetteEspece,
                    'recette_banque' => $recetteBanque,
                    'depense_espece' => $depenseEspece,
                    'depense_banque' => $depenseBanque,
                    'total_recette' => $totalRecette,
                    'total_depense' => $totalDepense,
                    'caisse' => $caisseValue
                ];

                $yearlyData['recettes'] += $totalRecette;
                $yearlyData['depenses'] += $totalDepense;
            }

            $yearlyData['net'] = $yearlyData['recettes'] - $yearlyData['depenses'];
            $financialData[$year] = $yearlyData;
        }

        // Calculate totals over all years
        $totalRecettes = array_sum(array_column($financialData, 'recettes'));
        $totalDepenses = array_sum(array_column($financialData, 'depenses'));
        $averageRecettes = $years ? $totalRecettes / count($years) : 0;
        $averageDepenses = $years ? $totalDepenses / count($years) : 0;

        return $this->render('dashboard/index.html.twig', [
            'financialData' => $financialData,
            'totalRecettes' => $totalRecettes,
            'totalDepenses' => $totalDepenses,
            'averageRecettes' => $averageRecettes,
            'averageDepenses' => $averageDepenses,
            'caisses' => $caisses,
            'isAdmin' => $isAdmin,
            'systemDate' => $systemDate,
            'caisseForm' => $form->createView(),
        ]);
    }

    #[Route('/dashboard/caisse/edit/{id}', name: 'edit_caisse')]
    public function editCaisse(
        Request $request,
        Caisse $caisse,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier si l'utilisateur est admin
        if (!$this->getUser() || $this->getUser()->getUserIdentifier() !== 'admin@admin.com') {
            throw $this->createAccessDeniedException('Seul l\'administrateur peut modifier une caisse.');
        }

        // Create form for editing Caisse
        $form = $this->createFormBuilder($caisse)
            ->add('solde', null, [
                'label' => 'Solde de la caisse',
                'attr' => ['step' => '0.01'],
            ])
            ->add('annee', IntegerType::class, [
                'label' => 'Année du solde (année précédente)',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si une autre caisse existe pour cette année (excluding current caisse)
            $existingCaisses = $entityManager->getRepository(Caisse::class)->findBy(['annee' => $caisse->getAnnee()]);
            $existingCaisse = null;
            foreach ($existingCaisses as $c) {
                if ($c->getId() !== $caisse->getId()) {
                    $existingCaisse = $c;
                    break;
                }
            }
            
            if ($existingCaisse) {
                $this->addFlash('error', sprintf('Une caisse existe déjà pour l\'année %s.', $caisse->getAnnee()));
            } else {
                $entityManager->flush();
                $this->addFlash('success', sprintf('La caisse pour l\'année %s a été modifiée avec succès.', $caisse->getAnnee()));
            }
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('dashboard/edit_caisse.html.twig', [
            'caisseForm' => $form->createView(),
            'caisse' => $caisse,
        ]);
    }

    #[Route('/dashboard/caisse/delete/{id}', name: 'delete_caisse', methods: ['POST'])]
    public function deleteCaisse(
        Request $request,
        Caisse $caisse,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier si l'utilisateur est admin
        if (!$this->getUser() || $this->getUser()->getUserIdentifier() !== 'admin@admin.com') {
            throw $this->createAccessDeniedException('Seul l\'administrateur peut supprimer une caisse.');
        }

        // Vérifier le token CSRF
        if ($this->isCsrfTokenValid('delete'.$caisse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($caisse);
            $entityManager->flush();
            $this->addFlash('success', sprintf('La caisse pour l\'année %s a été supprimée avec succès.', $caisse->getAnnee()));
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_dashboard');
    }
}