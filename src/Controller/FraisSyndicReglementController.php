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
            $appartementNom = $appartementObj->getNumero() ?? 'Appartement ' . $appartementKey;
            $personne = $f->getPersonne();
            $nom = $personne ? $personne->getNom() . ' ' . $personne->getPrenom() : '—';
            $proprietaire = $personne ? $personne->getNom() : '—';

            if (!isset($dataByYear[$year][$appartementKey])) {
                $dataByYear[$year][$appartementKey] = [
                    'nom' => $nom,
                    'appartement' => $appartementNom,
                    'appartementId' => $appartementKey,
                    'proprietaire' => $proprietaire,
                    'months' => array_fill(1, 12, 'Non'),
                    'details' => [],
                ];
            }

            foreach ($monthFields as $index => $month) {
                $getter = 'is' . $month;
                if ($f->$getter()) {
                    $dataByYear[$year][$appartementKey]['months'][$index + 1] = 'Oui';
                    $dataByYear[$year][$appartementKey]['details'][] = [
                        'id' => $f->getId(),
                        'montant' => $f->getTotale(),
                        'mois' => $month,
                        'annee' => $year,
                        'naturePaiement' => $f->getNaturePaiement() ? $f->getNaturePaiement()->getNature() : null,
                        'personne' => $nom,
                        'appartementId' => $appartementKey,
                        'appartementNom' => $appartementNom,
                        'utilisateur' => $f->getUser() ? $f->getUser()->getEmail() : null,
                    ];
                }
            }
        }

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
}