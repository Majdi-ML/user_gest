<?php

namespace App\Controller;

use App\Repository\CautionnementRepository;
use App\Repository\DepenseRepository;
use App\Repository\AppartementRepository;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        CautionnementRepository $cautionnementRepo,
        DepenseRepository $depenseRepo,
        AppartementRepository $appartementRepo,
        PersonneRepository $personneRepo
    ): Response {
        // Statistiques des cautionnements
        $cautionnementsByMonth = $cautionnementRepo->findMonthlyTotals();
        $cautionnementsByYear = $cautionnementRepo->findYearlyTotals();
        
        // Statistiques des dépenses
        $depensesByMonth = $depenseRepo->findMonthlyTotals();
        $depensesByYear = $depenseRepo->findYearlyTotals();
        $depensesByType = $depenseRepo->findTotalsByType();
        
        // Statistiques des appartements
        $appartementStats = $appartementRepo->findHabitationStats();
        $totalAppartements = $appartementRepo->count([]);
        
        // Derniers cautionnements
        $latestCautionnements = $cautionnementRepo->findLatest(5);
        
        // Dernières dépenses
        $latestDepenses = $depenseRepo->findLatest(5);
        
        // Locataires actifs
        $activeLocataires = $personneRepo->findActiveLocataires();
        dump($cautionnementsByMonth, $cautionnementsByYear, $depensesByMonth, $depensesByType);

        return $this->render('dashboard/index.html.twig', [
            'cautionnementsByMonth' => $cautionnementsByMonth,
            'cautionnementsByYear' => $cautionnementsByYear,
            'depensesByMonth' => $depensesByMonth,
            'depensesByYear' => $depensesByYear,
            'depensesByType' => $depensesByType,
            'appartementStats' => $appartementStats,
            'totalAppartements' => $totalAppartements,
            'latestCautionnements' => $latestCautionnements,
            'latestDepenses' => $latestDepenses,
            'activeLocataires' => $activeLocataires,
            
        ]);
    }
}