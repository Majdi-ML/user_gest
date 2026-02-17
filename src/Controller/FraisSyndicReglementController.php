<?php

namespace App\Controller;

use App\Entity\FraisSyndicReglement;
use App\Form\FraisSyndicReglementType;
use App\Repository\FraisSyndicReglementRepository;
use App\Repository\AppartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/frais_syndic_reglement')]
class FraisSyndicReglementController extends AbstractController
{
    #[Route('/get-personnes/{appartementId}', name: 'get_personnes_by_appartement')]
    public function getPersonnesByAppartement(int $appartementId, AppartementRepository $appartementRepo): JsonResponse
    {
        $appartement = $appartementRepo->find($appartementId);

        if (!$appartement) {
            return new JsonResponse(['error' => 'Appartement non trouvé'], 404);
        }

        $proprietaire = $appartement->getProprietaire();

        if (!$proprietaire) {
            return new JsonResponse([]);
        }

        $data = [
            [
                'id' => $proprietaire->getId(),
                'text' => $proprietaire->getNom() . ' ' . $proprietaire->getPrenom(),
            ]
        ];

        return new JsonResponse($data);
    }

    #[Route('/get-all-appartements', name: 'get_all_appartements')]
    public function getAllAppartements(AppartementRepository $appartementRepo): JsonResponse
    {
        $appartements = $appartementRepo->findAll();
        $data = [];

        foreach ($appartements as $appartement) {
            $proprietaire = $appartement->getProprietaire();
            $proprietaireNom = $proprietaire ? $proprietaire->getNom() . ' ' . $proprietaire->getPrenom() : 'Sans propriétaire';
            
            $data[] = [
                'id' => $appartement->getId(),
                'text' => 'Appart ' . $appartement->getNumero() . ' - ' . $proprietaireNom,
                'numero' => $appartement->getNumero(),
                'proprietaire' => $proprietaireNom,
            ];
        }

        return new JsonResponse($data);
    }

   #[Route('/', name: 'app_frais_syndic_reglement_index', methods: ['GET'])]
    public function index(Request $request, FraisSyndicReglementRepository $fraisSyndicReglementRepository, AppartementRepository $appartementRepository): Response
    {
        $fraisSyndicReglements = $fraisSyndicReglementRepository->findAll();
        $appartements = $appartementRepository->findAll();
        $totalMontant = 0;

        foreach ($fraisSyndicReglements as $f) {
            if ($f->getTotale()) {
                $totalMontant += $f->getTotale();
            }
        }

        $dataByYear = [];
        $monthFields = [
            'Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'
        ];

        // Initialize data for all apartments and years
        $years = array_unique(array_merge(
            array_map(fn($f) => $f->getAnnee(), $fraisSyndicReglements),
            [date('Y')] // Include current year
        ));
        
        // Sort years in descending order (current year first)
        rsort($years);

        foreach ($years as $year) {
            foreach ($appartements as $appartement) {
                $appartementKey = $appartement->getId();
                $personne = $appartement->getProprietaire();
                $nom = $personne ? $personne->getNom() . ' ' . $personne->getPrenom() : '—';
                $proprietaire = $personne ? $personne->getNom() : '—';
                $appartementNom = $appartement->getNumero() ?? 'Appartement ' . $appartementKey;

                if (!isset($dataByYear[$year])) {
                    $dataByYear[$year] = [];
                }

                $dataByYear[$year][$appartementKey] = [
                    'nom' => $nom,
                    'appartement' => $appartementNom,
                    'appartementId' => $appartementKey,
                    'proprietaire' => $proprietaire,
                    'months' => array_fill(1, 12, 'Non'),
                    'details' => [],
                ];
            }
        }

        // Populate payment data
        foreach ($fraisSyndicReglements as $f) {
            $year = $f->getAnnee();
            if (!$year) {
                continue;
            }

            $appartementObj = $f->getAppartement();
            if (!$appartementObj) {
                continue;
            }

            $appartementKey = $appartementObj->getId();
            
            // Skip if year/appartement not initialized
            if (!isset($dataByYear[$year][$appartementKey])) {
                continue;
            }

            // Add details for this reglement
            $dataByYear[$year][$appartementKey]['details'] = [
                'id' => $f->getId(),
                'montant' => $f->getTotale(),
                'annee' => $year,
                'naturePaiement' => $f->getNaturePaiement(),
                'personne' => $dataByYear[$year][$appartementKey]['nom'],
                'appartementId' => $appartementKey,
                'appartementNom' => $dataByYear[$year][$appartementKey]['appartement'],
                'utilisateur' => $f->getUser() ? $f->getUser()->getEmail() : null,
                'fraisId' => $f->getFrais() ? $f->getFrais()->getId() : null,
            ];

            // Mark paid months
            foreach ($monthFields as $index => $month) {
                $getter = 'is' . $month;
                if ($f->$getter()) {
                    $dataByYear[$year][$appartementKey]['months'][$index + 1] = 'Oui';
                }
            }
        }
        
        // Sort dataByYear by year in descending order (newest first)
        krsort($dataByYear);

        return $this->render('frais_syndic_reglement/index.html.twig', [
            'frais_syndic_reglements' => $fraisSyndicReglements,
            'dataByYear' => $dataByYear,
            'totalMontant' => $totalMontant,
        ]);
    }

   #[Route('/new', name: 'app_frais_syndic_reglement_new', methods: ['GET', 'POST'])]
public function new(
    Request $request,
    EntityManagerInterface $entityManager,
    Security $security,
    FraisSyndicReglementRepository $fraisSyndicReglementRepository,
    AppartementRepository $appartementRepository
): Response {
    $fraisSyndicReglement = new FraisSyndicReglement();
    $user = $security->getUser();

    // Pré-remplir appartement et année depuis les paramètres de requête
    $appartementId = $request->query->get('appartementId');
    $annee = $request->query->get('annee');

    if ($appartementId) {
        $appartement = $appartementRepository->find($appartementId);
        if ($appartement) {
            $fraisSyndicReglement->setAppartement($appartement);
            $fraisSyndicReglement->setPersonne($appartement->getProprietaire());
        }
    }

    if ($annee) {
        $fraisSyndicReglement->setAnnee($annee);
    }

    $form = $this->createForm(FraisSyndicReglementType::class, $fraisSyndicReglement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $appartements = $form->get('appartement')->getData();
        if (empty($appartements)) {
            $this->addFlash('error', 'Veuillez sélectionner au moins un appartement.');
            return $this->renderForm('frais_syndic_reglement/new.html.twig', [
                'frais_syndic_reglement' => $fraisSyndicReglement,
                'form' => $form,
            ]);
        }

        // Collecter les mois sélectionnés
        $selectedMonths = [];
        $monthFields = [
            'Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'
        ];
        foreach ($monthFields as $month) {
            $getter = 'is' . $month;
            if ($fraisSyndicReglement->$getter()) {
                $selectedMonths[] = $month;
            }
        }

        if (empty($selectedMonths)) {
            $this->addFlash('error', 'Veuillez sélectionner au moins un mois.');
            return $this->renderForm('frais_syndic_reglement/new.html.twig', [
                'frais_syndic_reglement' => $fraisSyndicReglement,
                'form' => $form,
            ]);
        }

        foreach ($appartements as $appartement) {
            $proprietaire = $appartement->getProprietaire();
            if (!$proprietaire) {
                $this->addFlash('error', sprintf(
                    'L\'appartement "%s" n\'a pas de propriétaire.',
                    $appartement->getNom() ?? 'Appartement ' . $appartement->getId()
                ));
                return $this->renderForm('frais_syndic_reglement/new.html.twig', [
                    'frais_syndic_reglement' => $fraisSyndicReglement,
                    'form' => $form,
                ]);
            }

            // Vérifier si un règlement existe déjà pour cet appartement, propriétaire et année
            $existingReglement = $fraisSyndicReglementRepository->findOneBy([
                'Personne' => $proprietaire,
                'annee' => $fraisSyndicReglement->getAnnee(),
                'appartement' => $appartement,
            ]);

            // Si un règlement existe, vérifier les mois déjà payés
            if ($existingReglement) {
                foreach ($selectedMonths as $month) {
                    $getter = 'is' . $month;
                    if ($existingReglement->$getter()) {
                        $this->addFlash('error', sprintf(
                            'Le propriétaire %s a déjà payé les frais de syndic pour le mois %s %s pour cet appartement.',
                            $proprietaire->getNom() . ' ' . $proprietaire->getPrenom(),
                            $month,
                            $fraisSyndicReglement->getAnnee()
                        ));
                        return $this->renderForm('frais_syndic_reglement/new.html.twig', [
                            'frais_syndic_reglement' => $fraisSyndicReglement,
                            'form' => $form,
                        ]);
                    }
                }
            }

            // Utiliser le règlement existant ou en créer un nouveau
            $reglement = $existingReglement ?: new FraisSyndicReglement();
            $reglement->setFrais($fraisSyndicReglement->getFrais());
            $reglement->setAnnee($fraisSyndicReglement->getAnnee());
            $reglement->setPersonne($proprietaire);
            $reglement->setAppartement($appartement);
            $reglement->setNaturePaiement($fraisSyndicReglement->getNaturePaiement());
            $reglement->setUser($user);

            // Si c'est un nouveau règlement, initialiser tous les mois à false
            if (!$existingReglement) {
                foreach ($monthFields as $month) {
                    $setter = 'set' . $month;
                    $reglement->$setter(false);
                }
            }

            // Mettre à jour les mois sélectionnés et calculer le total
            $montantMensuel = $fraisSyndicReglement->getFrais()->getMontant();
            $total = $existingReglement ? $reglement->getTotale() : 0;
            foreach ($selectedMonths as $month) {
                $setter = 'set' . $month;
                $reglement->$setter(true);
                $total += $montantMensuel;
            }
            $reglement->setTotale($total);

            $entityManager->persist($reglement);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Les frais de syndic ont été enregistrés avec succès.');
        return $this->redirectToRoute('app_frais_syndic_reglement_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('frais_syndic_reglement/new.html.twig', [
        'frais_syndic_reglement' => $fraisSyndicReglement,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_frais_syndic_reglement_show', methods: ['GET'])]
    public function show(FraisSyndicReglement $fraisSyndicReglement): Response
    {
        return $this->render('frais_syndic_reglement/show.html.twig', [
            'frais_syndic_reglement' => $fraisSyndicReglement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_frais_syndic_reglement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FraisSyndicReglement $fraisSyndicReglement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FraisSyndicReglementType::class, $fraisSyndicReglement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $montantMensuel = $fraisSyndicReglement->getFrais()->getMontant();
            $total = 0;
            $monthFields = [
                'Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'
            ];
            foreach ($monthFields as $month) {
                if ($fraisSyndicReglement->{'is' . $month}()) {
                    $total += $montantMensuel;
                }
            }
            $fraisSyndicReglement->setTotale($total);
            $entityManager->flush();

            return $this->redirectToRoute('app_frais_syndic_reglement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frais_syndic_reglement/edit.html.twig', [
            'frais_syndic_reglement' => $fraisSyndicReglement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_frais_syndic_reglement_delete', methods: ['POST'])]
    public function delete(Request $request, FraisSyndicReglement $fraisSyndicReglement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fraisSyndicReglement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fraisSyndicReglement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_frais_syndic_reglement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/ajax/get-montant-mensuel', name: 'app_frais_syndic_reglement_get_montant', methods: ['GET'])]
    public function getMontantMensuel(EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $frais = $entityManager->getRepository(\App\Entity\FraisSyndic::class)->findOneBy([]);
            if (!$frais) {
                return new JsonResponse(['success' => false, 'message' => 'Aucun frais syndic défini'], 404);
            }
            
            return new JsonResponse([
                'success' => true,
                'montant' => $frais->getMontant()
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/ajax/get-all-frais', name: 'app_frais_syndic_reglement_get_all_frais', methods: ['GET'])]
    public function getAllFrais(EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $fraisList = $entityManager->getRepository(\App\Entity\FraisSyndic::class)->findAll();
            $data = [];
            
            foreach ($fraisList as $frais) {
                $data[] = [
                    'id' => $frais->getId(),
                    'montant' => $frais->getMontant()
                ];
            }
            
            return new JsonResponse([
                'success' => true,
                'frais' => $data
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/ajax/get/{id}', name: 'app_frais_syndic_reglement_get_ajax', methods: ['GET'])]
    public function getAjax(int $id, FraisSyndicReglementRepository $fraisSyndicReglementRepository): JsonResponse
    {
        try {
            $reglement = $fraisSyndicReglementRepository->find($id);
            
            if (!$reglement) {
                return new JsonResponse(['success' => false, 'message' => 'Règlement non trouvé'], 404);
            }
            
            $monthFields = [
                'Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'
            ];
            
            $paidMonths = [];
            foreach ($monthFields as $index => $month) {
                $getter = 'is' . $month;
                if ($reglement->$getter()) {
                    $paidMonths[] = $index + 1; // Month number (1-12)
                }
            }
            
            $data = [
                'success' => true,
                'id' => $reglement->getId(),
                'appartementId' => $reglement->getAppartement() ? $reglement->getAppartement()->getId() : null,
                'annee' => $reglement->getAnnee(),
                'montant' => $reglement->getTotale(),
                'naturePaiement' => $reglement->getNaturePaiement(),
                'fraisId' => $reglement->getFrais() ? $reglement->getFrais()->getId() : null,
                'paidMonths' => $paidMonths,
            ];
            
            return new JsonResponse($data);
            
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/ajax/save', name: 'app_frais_syndic_reglement_save_ajax', methods: ['POST'])]
    public function saveAjax(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
        FraisSyndicReglementRepository $fraisSyndicReglementRepository,
        AppartementRepository $appartementRepository
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                return new JsonResponse(['success' => false, 'message' => 'Données invalides'], 400);
            }
            
            $appartementId = $data['appartementId'] ?? null;
            $annee = $data['annee'] ?? null;
            $months = $data['months'] ?? [];
            $naturePaiement = $data['naturePaiement'] ?? 'espece';
            $montantTotal = $data['montant'] ?? null;
            $fraisId = $data['fraisId'] ?? null;
            $mode = $data['mode'] ?? 'create';
            $reglementId = $data['id'] ?? null;
            
            error_log("SAVE AJAX - Mode: $mode, ReglementID: " . ($reglementId ?? 'NULL'));
            error_log("SAVE AJAX - Data: " . json_encode($data));
            
            if (!$appartementId || !$annee || empty($months)) {
                return new JsonResponse(['success' => false, 'message' => 'Données manquantes'], 400);
            }
            
            if (!$montantTotal || $montantTotal <= 0) {
                return new JsonResponse(['success' => false, 'message' => 'Montant invalide'], 400);
            }
            
            if (!$fraisId) {
                return new JsonResponse(['success' => false, 'message' => 'Veuillez sélectionner un montant mensuel'], 400);
            }
            
            // Get appartement
            $appartement = $appartementRepository->find($appartementId);
            if (!$appartement) {
                return new JsonResponse(['success' => false, 'message' => 'Appartement non trouvé'], 404);
            }
            
            $proprietaire = $appartement->getProprietaire();
            if (!$proprietaire) {
                return new JsonResponse(['success' => false, 'message' => 'L\'appartement n\'a pas de propriétaire'], 400);
            }
            
            // Get or create reglement
            $reglement = null;
            if ($mode === 'edit' && $reglementId) {
                $reglement = $fraisSyndicReglementRepository->find($reglementId);
                if (!$reglement) {
                    return new JsonResponse(['success' => false, 'message' => 'Règlement non trouvé'], 404);
                }
                // Reset all months first
                $monthFields = [
                    'Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin',
                    'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'
                ];
                foreach ($monthFields as $month) {
                    $setter = 'set' . $month;
                    $reglement->$setter(false);
                }
            } else {
                // Check if reglement already exists
                $reglement = $fraisSyndicReglementRepository->findOneBy([
                    'Personne' => $proprietaire,
                    'annee' => $annee,
                    'appartement' => $appartement,
                ]);
                
                if (!$reglement) {
                    $reglement = new FraisSyndicReglement();
                    $reglement->setPersonne($proprietaire);
                    $reglement->setAppartement($appartement);
                    $reglement->setAnnee($annee);
                    
                    // Initialize all months to false
                    $monthFields = [
                        'Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin',
                        'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'
                    ];
                    foreach ($monthFields as $month) {
                        $setter = 'set' . $month;
                        $reglement->$setter(false);
                    }
                } else {
                    // Reset existing months
                    $monthFields = [
                        'Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin',
                        'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'
                    ];
                    foreach ($monthFields as $month) {
                        $setter = 'set' . $month;
                        $reglement->$setter(false);
                    }
                }
            }
            
            // Set nature paiement
            $reglement->setNaturePaiement($naturePaiement);
            
            // Set user
            $user = $security->getUser();
            $reglement->setUser($user);
            
            // Get frais by ID
            $frais = $entityManager->getRepository(\App\Entity\FraisSyndic::class)->find($fraisId);
            if (!$frais) {
                return new JsonResponse(['success' => false, 'message' => 'Montant mensuel non trouvé'], 404);
            }
            $reglement->setFrais($frais);
            
            $monthNames = [
                1 => 'Janvier', 2 => 'Fevrier', 3 => 'Mars', 4 => 'Avril', 
                5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Aout', 
                9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Decembre'
            ];
            
            // Set selected months
            foreach ($months as $monthNum) {
                if (isset($monthNames[$monthNum])) {
                    $monthName = $monthNames[$monthNum];
                    $setter = 'set' . $monthName;
                    $reglement->$setter(true);
                }
            }
            
            // Use provided montant instead of calculating
            $reglement->setTotale($montantTotal);
            
            $entityManager->persist($reglement);
            $entityManager->flush();
            
            return new JsonResponse([
                'success' => true,
                'message' => 'Règlement enregistré avec succès',
                'id' => $reglement->getId()
            ]);
            
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}