<?php

namespace App\Controller;

use App\Repository\FraisSyndicReglementRepository;
use App\Repository\DepenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        FraisSyndicReglementRepository $fraisRepo,
        DepenseRepository $depenseRepo,
        EntityManagerInterface $entityManager
    ): Response {
        // Fetch distinct years from FraisSyndicReglement
        $fraisYears = $fraisRepo->createQueryBuilder('f')
            ->select('DISTINCT f.annee')
            ->getQuery()
            ->getSingleColumnResult();

        // Fetch distinct years from Depense using native SQL
        $connection = $entityManager->getConnection();
        $sql = "SELECT DISTINCT strftime('%Y', date_depense) as year FROM depense";
        $stmt = $connection->executeQuery($sql);
        $depenseYears = array_column($stmt->fetchAllAssociative(), 'year');

        // Combine and sort unique years
        $years = array_unique(array_merge($fraisYears, $depenseYears));
        sort($years);

        // Current system date for Solde Ancien Syndic
        $systemDate = new \DateTime();

        // Mois pour affichage et champs sans accents pour la requête
        $months = [
            'Janvier' => 'Janvier',
            'Février' => 'fevrier',
            'Mars' => 'mars',
            'Avril' => 'avril',
            'Mai' => 'mai',
            'Juin' => 'juin',
            'Juillet' => 'juillet',
            'Août' => 'aout',
            'Septembre' => 'septembre',
            'Octobre' => 'octobre',
            'Novembre' => 'novembre',
            'Décembre' => 'decembre'
        ];

        // Fetch all NaturePaiement types
        $naturePaiements = $fraisRepo->createQueryBuilder('f')
            ->select('DISTINCT np.nature')
            ->join('f.nature_paiement', 'np')
            ->getQuery()
            ->getSingleColumnResult();

        // Initial balance (hardcoded from PDF; adjust if dynamic)
        $initialCaisse = 1725000;
        $caisse = $initialCaisse;

        $financialData = [];

        foreach ($years as $year) {
            $yearlyData = [
                'recettes' => 0,
                'depenses' => 0,
                'months' => []
            ];

            $monthLabels = array_keys($months);
            $monthFields = array_values($months);

            $monthLimit = ($year == 2025) ? min(5, count($months)) : 12;

            for ($i = 0; $i < $monthLimit; $i++) {
                $monthName = $monthLabels[$i];
                $monthField = $monthFields[$i];
                $monthNum = str_pad($i + 1, 2, '0', STR_PAD_LEFT);

                // Fetch receipts by NaturePaiement
                $recettesByNature = [];
                foreach ($naturePaiements as $nature) {
                    $recettesByNature[$nature] = $fraisRepo->createQueryBuilder('f')
                        ->select('SUM(f.totale)')
                        ->join('f.nature_paiement', 'np')
                        ->where('f.annee = :year')
                        ->andWhere("f.$monthField = true")
                        ->andWhere('np.nature = :nature')
                        ->setParameters(['year' => $year, 'nature' => $nature])
                        ->getQuery()
                        ->getSingleScalarResult() ?? 0;
                }

                // Total receipts for the month
                $recettes = array_sum($recettesByNature);

                // Fetch expenses using native SQL
                $sql = "SELECT COALESCE(SUM(montant), 0) as total FROM depense WHERE strftime('%Y', date_depense) = :year AND strftime('%m', date_depense) = :month";
                $stmt = $connection->executeQuery($sql, ['year' => $year, 'month' => $monthNum]);
                $depenses = (float) $stmt->fetchOne();

                // Update CAISSE
                $caisse += $recettes - $depenses;

                $yearlyData['months'][$monthName] = [
                    'recettes' => $recettes,
                    'recettesByNature' => $recettesByNature,
                    'depenses' => $depenses,
                    'caisse' => $caisse
                ];

                $yearlyData['recettes'] += $recettes;
                $yearlyData['depenses'] += $depenses;
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
            'initialCaisse' => $initialCaisse,
            'systemDate' => $systemDate,
            'naturePaiements' => $naturePaiements,
        ]);
    }
}
